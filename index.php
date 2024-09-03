<?php

include_once __DIR__ . "/layout/header.php";
include_once __DIR__ . './core/inti.php';

$current_user_id = $_SESSION['user']['uuid'];
// $reciever_token = $_GET['usrid'];
random_int(100, 10000)

?>
    <div class="container-fluid chat-container">
        <div class="row h-100">
            <!-- Contacts List -->
            <div class="col-3 p-0 chat-list">
                <?php include __DIR__ ."/layout/conversations.php"; ?>
                <?php include __DIR__ ."/layout/new_chats.php"; ?>

            </div>

            <!-- Chat Window -->
            <div class="col-9 p-0 d-flex flex-column chat-window">
                <!-- Chat Header -->
                <div class="chat-header">
                    <img src="https://via.placeholder.com/40" alt="User">
                    <div class="username">John Doe</div>
                </div>

                <!-- Chat Messages -->
                <div class="chat-messages">
                    <div class="chat-message received">
                        <img src="https://via.placeholder.com/40" alt="User">
                        <div class="message-bubble received">
                            Hey, how are you?
                        </div>
                    </div>
                    <div class="chat-message sent">
                        <div class="message-bubble sent">
                            I'm good, thanks! How about you?
                        </div>
                        <img src="https://via.placeholder.com/40" alt="User">
                    </div>
                    <div class="chat-message received">
                        <img src="https://via.placeholder.com/40" alt="User">
                        <div class="message-bubble received">
                            Doing great! Just working on a project.
                        </div>
                    </div>
                </div>

                <!-- Chat Input -->
                <!-- Chat Input -->
                <div class="chat-input">

                    <form id="chatForm" action="" method="post">
                        <textarea id="messageFormField" class="form-control" name="msg" placeholder="Type a message..."></textarea>
                        <button class="btn btn-primary send-btn">Send</button>
                    </form>

                </div>
            </div>
        </div>
    </div><script>
    document.addEventListener('DOMContentLoaded', () => {

        var conn = new WebSocket('ws://chatapp.test:8080?token=<?php echo $token ?>')
        const messageForm = document.forms[0];
        const message = document.getElementById('messageFormField')
        const sender = document.getElementById('snd')
        const receiver = document.getElementById('rcv')
        const messageContainer = document.querySelector('.message-box')

        var receiverToken = '';
        const senderToken = '<?php echo $token ;?>'
       
        const chatWrapper =  document.querySelector('.chat-list');
        const chats = document.querySelectorAll('.chat-list-item')

        chats.forEach(function(chat){
            chat.addEventListener('click', function(e){
                if(e.target == chat){
                    receiverToken = chat.dataset.token;
                    receiverToken = chat.dataset.uuid;
                }else{
                    receiverToken = chat.dataset.token;
                    receiverToken = chat.dataset.uuid;
                }
            })
        })


        conn.onopen = (e) => {

            // checkOnlineStatus();

            conn.onmessage = function(e){
                console.log(e.data);
                createNewMessageBox('div', 'incoming-message', e.data)
            }

            // conn.addEventListener('message', function(e) {
            //     console.log(`Message froms server: ${e.data}`)
            //     createNewMessageBox('div', 'incoming-message', e.data)
            // })
        }


        messageForm.onsubmit = (e) => {
                e.preventDefault();

                msg = {
                    senderToken: senderToken,
                    recieverToken: receiverToken,
                    message: message.value,
                }
                conn.send(JSON.stringify(msg))
                createNewMessageBox('div', 'outgoing-message', msg.message)
                message.value = ""
            }


        // function checkOnlineStatus() {
        //     setInterval(function() {
        //         console.log('checking user online status')
        //         conn.send({
        //             'status': 'online',
        //             'userID': userID
        //         })
        //     }, 5000)
        // }


        function createNewMessageBox(elementName, className, message) {
            const el = document.createElement(elementName)
            el.classList.add(className)
            el.textContent = message
            messageContainer.appendChild(el);
        }

    })
</script>

<?php include_once __DIR__ . "/layout/footer.php"  ?>