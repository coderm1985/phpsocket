<?php
    include('header.php');
?>
    <div class="container"> 
         <?php
            
            if(isset($_POST['join'])){
                session_start();
                require('db/users.php');
                $objUser = new users;
                $objUser->setEmail($_POST['email']);
                $objUser->setName($_POST['username']);
                $objUser->setLoginStatus(1);
                $objUser->setLastLogin(date('Y-m-d h:i:s'));
                $userData = $objUser->getUserByEmail();
                
                if(is_array($userData) && count($userData)>0){
                    $objUser->setId($userData['id']);
                    if($objUser->updateLoginStatus()){
                        $_SESSION['user'][$userData['id']]=$userData;
                        header('location:chatroom.php');
                        echo 'User login..';
                    }
                    else{
                        echo "Failed to Login..";
                    }
                }
                else{
                    if($objUser->save()){
                        $lastId = $objUser->dbConn->lastInsertId();
                        $objUser->setId($lastId);
                        $_SESSION['user'][$userData['id']]=(array)$objUser;
                        header('location:chatroom.php');
                        echo "Saved..";
                    }else{
                        echo "Failed..";
                    }
                }
            }
         ?>
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form id="loginform" class="form-horizontal" role="form" method="post" action="">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="Enter Name">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="email" type="text" class="form-control" name="email" placeholder="Enter Email Address">
                                    </div>


                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->

                                    <div class="col-sm-12 controls">
                                      <button id="join" name="join" type="submit" class="btn btn-success">Join Chat Room</button>

                                    </div>
                                </div>

                            </form>     
                        </div>                     
                    </div>  
        </div>
        
<?php
    include('footer.php');
?>
