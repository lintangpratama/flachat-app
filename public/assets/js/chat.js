
document.addEventListener("DOMContentLoaded", function (e) {
    // handel click friend
    document.querySelectorAll(".friends").forEach(function (el) {
        el.addEventListener("click", function () {
            let id = el.getAttribute("data-id");
            let name = el.getAttribute("data-name");
            let avatar = el.getAttribute("data-avatar");
            // set chat room properties
            document.querySelector(".friend-name").innerHTML = name;
            document.querySelector(
                ".header-img"
            ).innerHTML = `<img src="${avatar}" class="rounded-circle user_img"/>`;

            createRoom(id, avatar);
        });
    });
});

/*
    handel send message function
 */
function sendMessage(message, roomId) {
    let url = document.getElementById("message-url").value;
    let formData = new FormData();

    formData.append("roomId", roomId);
    formData.append("message", message);

    axios.post(url, formData).then((res) => {
        let html =
            ' <div id="your-chat" class="your-chat">\n' +
            '                <p class="your-chat-balloon">' +
            message +
            "</p>\n" +
            "            </div>";

        var chatBody = document.querySelector("#chat-area");
        chatBody.insertAdjacentHTML("beforeend", html);
        chatBody.scrollTo({
            left: 0,
            top: chatBody.scrollHeight,
            behavior: "smooth",
        });
    });
}

/*
    handel to left message from friend
 */
function handelLeftMessage(message, avatar) {
    let html =
        '<div class="friends-chat">\n' +
        '                <div class="profile friends-chat-photo">\n' +
        '                    <img src="' +
        avatar +
        '" alt="">\n' +
        "                </div>\n" +
        '                <div class="friends-chat-content">\n' +
        '                    <p class="friends-chat-name">' +
        message +
        "</p>\n" +
        "                </div>\n" +
        "            </div>";

    var chatBody = document.querySelector("#chat-area");
    chatBody.insertAdjacentHTML("beforeend", html);
    chatBody.scrollTo({
        left: 0,
        top: chatBody.scrollHeight,
        behavior: "smooth",
    });
}

/*
    handel show hide chatbox
 */
function showHideChatBox(show) {
    if (show == true) {
        document.getElementById("main-right").classList.remove("hidden");
        document.getElementById("main-empty").classList.add("hidden");
    } else {
        document.getElementById("main-right").classList.add("hidden");
        document.getElementById("main-empty").classList.remove("hidden");
    }
}

function createRoom(friendId, avatar) {
    let url = document.getElementById("room-url").value;
    var chatBody = document.querySelector("#chat-area");

    chatBody.replaceChildren()
    let formData = new FormData();
    formData.append("friend_id", friendId);
    

    axios.post(url, formData).then(function (res) {

        let room = res.data.data;
        Echo.leave(`chat.${room.id}`);
        console.log(room);
        
        Echo.join(`chat.${room.id}`)
            .here((users) => {
                console.log("Join success");
              
                loadMessage(room.id, friendId, avatar);
                document
                    .querySelector("#type-area")
                    .addEventListener("keydown", function (e) {
                        if (e.key === "Enter") {
                            let input = this.value;
                            if (input !== "") {
                                sendMessage(input, room.id);

                                this.value = "";
                            }
                        }
                    });
            }).listen(`SendMessage`, function (e) {
                if (e.userId == friendId){
                    handelLeftMessage(e.message, avatar)
                }
            })
            .joining((user) => {
                console.log(`user join as ${user.name}`);

                  loadMessage(room.id, friendId, avatar);
                document
                    .querySelector("#type-area")
                    .addEventListener("keydown", function (e) {
                        if (e.key === "Enter") {
                            let input = this.value;
                            if (input !== "") {
                                sendMessage(input, room.id);

                                this.value = "";
                            }
                        }
                    });
                
                document.querySelectorAll(".friends").forEach(function (el) {
                    if (el.getAttribute("data-id")==user.id){
                        el.querySelector(".friends-credent > .friend-status").innerHTML = "<p> online </p>"
                    }
                })
            })
            .leaving((user) => {
                console.log(`user meninggalkan room ${user.name}`);
            })
            .error((error) => {
                console.error(error);
            })
            .listen(`.SendMessage`, (e) => {
                //]
                if (e.userId == friendId){
                    handelLeftMessage(e.message, avatar)
                }
            });
        showHideChatBox(true);
    });
}

function loadMessage(roomId, friendId, avatar) {
    let url = document.getElementById("load-chat-url").value;
    url = url.replace(":roomId", roomId);

    axios.get(url).then((res) => {
        let data = res.data.data;
        console.log(data.length)
        if (data.length > 0) {
            data.forEach(function (value) {
                if (value.user_id==friendId) {
                    handelLeftMessage(value.message, avatar)
                }
                else{
                    let html =
                    ' <div id="your-chat" class="your-chat">\n' +
                    '                <p class="your-chat-balloon">' +
                    value.message +
                    "</p>\n" +
                    "            </div>";
                    var chatBody = document.querySelector("#chat-area");
                    chatBody.insertAdjacentHTML("beforeend", html);
                    chatBody.scrollTo({
                        left: 0,
                        top: chatBody.scrollHeight,
                        behavior: "smooth",
                    });
                }
            })

        } else {
            document.querySelector(".chat-area").innerHTML =
                "<p style='text-align: center' >Tidak memiliki pesan</p>";
        }
    });
}
