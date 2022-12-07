
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
                    ' <div id="your-chat" class="d-flex justify-content-end mb-4">\n' +
                    '                <div class="msg_cotainer_send">' +
                    message +
                    "</div>\n " +
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
        '<div class="friends-chat d-flex justify-content-start mb-4">\n' +
        '                <div class="profile friends-chat-photo img_cont_msg">\n' +
        '                    <img src="' +
        avatar +
        '" alt=""  class="rounded-circle user_img_msg">\n' +
        "                </div>\n" +
        '     <div class="msg_cotainer">' +
        message +
        "</div>\n " +
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
        document.getElementById("chat-container").classList.remove("hidden");
        document.getElementById("main-empty").classList.add("hidden");
    } else {
        document.getElementById("chat-container").classList.add("hidden");
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
    var countmess = document.querySelector("#count-container");
    

    axios.get(url).then((res) => {
        let data = res.data.data;
        countmess.innerText =  (data.length) + " Message"

        if (data.length > 0) {
            data.forEach(function (value) {
                if (value.user_id==friendId) {
                    handelLeftMessage(value.message, avatar)
                }
                else{
                    let html =
                    ' <div id="your-chat" class="d-flex justify-content-end mb-4">\n' +
                    '                <div class="msg_cotainer_send">' +
                    value.message + 
                    " </div>\n " +
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
