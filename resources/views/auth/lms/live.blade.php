@extends('auth.main-lms')

@section('head')
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        .video-container {
            display: flex;
            height: calc(100vh - 70px); /* Adjust the 70px value based on the height of your head section */
            padding-bottom: 20px; /* Add padding-bottom to create space */
        }
        .video-player {
            flex: 2;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
        }
        .chat-container {
            flex: 1;
            border-left: 1px solid #ccc;
            display: flex;
            flex-direction: column;
        }
        .chat-header, .chat-footer {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .chat-footer {
            border-top: 1px solid #ccc;
            border-bottom: none;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }
        .chat-input {
            width: calc(100% - 80px);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .send-button {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
    </style>
@endsection

@section('contents')
    <div class="video-container">
        <div class="video-player">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $workshop->youtube_video_id }}" title="🔴 LIVE: Internet Networks &amp; Network Security | Google Cybersecurity Certificate" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
        <div class="chat-container">
            <div class="chat-header">
                {{ $workshop->workshop_title  }}
            </div>
            <div class="chat-messages">
                <iframe width="100%" height="100%" src="https://www.youtube.com/live_chat?v={{ $workshop->youtube_video_id }}&embed_domain={{ request()->getHost() }}" frameborder="0"></iframe>
            </div>
            <div class="chat-footer d-flex">
                <input type="text" class="chat-input" placeholder="Tin nhắn cho lớp ...">
                <button class="send-button ml-2">Send</button>
            </div>
        </div>
    </div>
@endsection
