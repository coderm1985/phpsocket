<?php include('header.php'); ?>
    
    <div class="row">
        <div class="col-md-4">
            <?php 
                session_start();
                //print_r($_SESSION);
                require('db/users.php');
                require('db/chatrooms.php');

                $objChatroom = new chatrooms;
                $chatrooms = $objChatroom->getAllChatRooms();

                $objUser = new users;
                $users = $objUser->getAllUsers();
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>
                            <?php 
                              foreach($_SESSION['user'] as $key => $user)
                              {
                                echo '<input type="hidden" name="userId" id="userId" value="'.$key.'">';
                                echo "<div>".$user['name']."</div>";
                                echo "<div>".$user['email']."</div>";
                              }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">
                              Users
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                        foreach($users as $key => $user)
                        {
                            $color = "red";
                            if($user['login_status']==1){
                                $color='color:green';
                            }
                            if(!isset($_SESSION['user'][$user['id']])){
                                echo "<tr><td>".$user['name']."</td>";
                                echo "<td><span class='glyphicon glyphicon-globe' style=".$color."></span></td>";
                                echo "<td>".$user['last_login']."</td></tr>";
                            }
                        }
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <div id="messages">
                <table id="chats" class="table table-striped">
                        <thead>
                            <tr>
                                <th colspan="4" scope="col">
                                    <strong>Chat Room</strong>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($chatrooms as $key => $chatroom){
                                    if(isset($_SESSION['user'][$chatroom['userid']])){
                                        $from = "Me";
                                    }else{
                                        $from = $chatroom['name'];
                                    }
                                    echo '<tr><td valign="top"><div><strong>'.$from.'</strong></div><div>'.$chatroom['msg'].'</div></td><td align="right" valign="top">'.$chatroom['created_on'].'</td></tr>';
                                }
                            ?>
                            <!-- <tr>
                                <td valign="top">
                                    <div><strong>From </strong></div>
                                    <div>Message</div>
                                </td>
                                <td align="right" valign="top">Message time</td>
                            </tr> -->
                        </tbody>
                </table>
            </div>
            <form id="chat-room-frm" method="post" action="">
                <div class="form-group">
                    <textarea class="form-control" id="msg" name="msg" placeholder="Enter Message"></textarea>
                </div>
                <div class="form-group">
                    <input type="button" value="Send" class="btn btn-success btn-block" id="send" name="send">
                </div>
            </form>
        </div>
    </div>    
<script>
    $(document).ready(function(){
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
        console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            console.log(e.data);
            var data = JSON.parse(e.data)
            var row = '<tr><td valign="top"><div><strong>'+data.from+ '</strong></div><div>'+data.msg+'</div></td><td align="right" valign="top">'+data.dt+'</td></tr>';
            $("#chats  > tbody").prepend(row);
        };

        $("#send").on("click",function(){
            var userId = $("#userId").val();
            var msg = $("#msg").val();
            var data = {
                userId:userId,
                msg:msg
            };
            conn.send(JSON.stringify(data));
            $("#msg").val("");
        });
    });
</script>
<?php include('footer.php'); ?>