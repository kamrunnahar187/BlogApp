@extends('layouts.app')
<style type="text/css">
    .avatar{
       border-radius: 100%;
       max-width: 100px ;
    }
    </style>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        @if(count($errors) > 0)
                    @foreach($error->all() as $error)
                        <div class ="alert alert-danger">{{$error}}</div>
                    @endforeach
                @endif
                
                @if(session('response'))
                    <div class ="alert alert-success">{{session('response')}}</div>
                @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-4">Dashboard</div>
                            <div class="col-lg-8">
                    <form action="/search" method="POST" role="search">
                        {{ csrf_field() }}
                    <div class="input-group">
                     <input type="text" class="form-control" name="search"
            placeholder="Search for..."> <span class="input-group-btn">
            <button type="submit" class="btn btn-default">
                Go!
            </button>
        </span>
    </div>
</form>
</div>
</div>
</div>
                </div>

                <div class="panel-body">
                    <div class="col-md-4">
                    @if(!empty($profile))
                    <img src="{{ $profile->profile_pic }}" class="avatar" alt="">
                    @endif
                    @if(!empty($profile))
                    <p class="lead">{{ $profile->name }}</p>
                    @endif
                    @if(!empty($profile))
                    <p class="lead">{{ $profile->designation }}</p>
                    @endif
                    </div>
                    <div class="col-md-8">
                     @if(count($posts) > 0)
                         @foreach($posts->all() as $post)
                        <center>
                        <h4> {{$post->post_title}}</h4>
                        <img src="{{$post->post_image}}" style="height:200px; width=200px;">
                        
                        <p>{{substr($post->post_body, 0, 150)}}</p> </center>

                        <ul class="nav nav-pills">
                            <li role="presentation">
                               <a href="{{url("/view/{$post->id}")}}">
                                   <span class="fa fa-eye"> VIEW</span>
                               </a> 
                            </li>
                             <li role="presentation">
                               <a href="{{url("/edit/{$post->id}")}}">
                                   <span class="fa fa-pencil-square-o"> Edit</span>
                               </a> 
                            </li>

                                <li role="presentation">
                               <a href="{{url("/delete/{$post->id}")}}">
                                   <span class="fa fa-trash"> Delete</span>
                               </a> 
                            </li>


                        </ul>
                        <cite style="">Posted on:{{date('M j, Y H:i', strtotime($post->updated_at))}}</cite>
                        <hr/>  
                        @endforeach
                       
                     @else
                        <p>No Post Available</p>
                        @endif  
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
