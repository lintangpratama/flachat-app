<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat App Template</title>
    <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row justify-content-center h-100">
            <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
                <div class="card-header">
                    <div class="input-group">
                        <input type="text" placeholder="Search..." name="" class="form-control search">
                        <div class="input-group-prepend">
                            <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
                <div class="card-body contacts_body">
                    <ul class="contacts">
                        @foreach($friends as $friend)
                        @php
                        $avatar = "ava".(rand(1,8)).".jpg";
                        @endphp
                        <div class="friends" data-id="{{ $friend->id }}" data-name="{{ $friend->name }}" data-avatar="{{ asset("assets/images"."/".$avatar) }}">
                    
                        <li class="">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont profile friends-photo">
                                    <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                                    <span class="online_icon"></span>
                                </div>
                                <div class="user_info friends-credent">
                                    <span class="friends-name">{{ $friend->name }}</span>
                                    <p>Kalid is online</p>
                                </div>
                            </div>
                        </li>
                        </div>
                   @endforeach
                    </ul>
                </div>
                <div class="card-footer"></div>
            </div></div>
            
            <div class="col-md-8 col-xl-6 chat">
                <section id="main-empty" class="main-right">
                    <p style="text-align: center; font-size: 35px">Welcome to mini chat</p>
                </section>
                <div class="card hidden" id="chat-container">
                    <div class="card-header msg_head">
                        <div class="d-flex bd-highlight">
                            <div class="img_cont header-img"  id="header-img" >
                                <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                                <span class="online_icon"></span>
                            </div>
                            <div class="user_info">
                                <span class="name friend-name">Mario Gomez</span>
                                <p id="count-container">x Messages</p>
                            </div>
                           
                        </div>
                        <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
                        <div class="action_menu">
                            <ul>
                                <li><i class="fas fa-user-circle"></i> View profile</li>
                                <li><i class="fas fa-users"></i> Add to close friends</li>
                                <li><i class="fas fa-plus"></i> Add to group</li>
                                <li><i class="fas fa-ban"></i> Block</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body msg_card_body chat-area" id="chat-area">
                        
                    
                       
                    </div>
                    <div class="card-footer">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                            </div>
                            <textarea name="" id="type-area"  class=" type-area form-control type_msg" placeholder="Type your message..."></textarea>
                            <div class="input-group-append">
                                <span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<input type="hidden" name="" id="room-url" value="{{route("room.create")}}">
<input type="hidden" name="" id="message-url" value="{{route("chat.save")}}">
<input type="hidden" name="" id="load-chat-url" value="{{route("chat.load", ["roomId" => ":roomId"])}}">
@vite('resources/js/app.js')
<script src="{{ asset("assets/js/js.js") }}"></script>
</body>

</html>