<?php
require 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Waiting Room</title>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="SetGame" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <?php
                        $roomAdmin = $_SESSION['roomID_admin'];
                        $sql = "SELECT bay.id_bay, bay.nama_bay, bay.detail_bay FROM bay INNER JOIN room ON bay.id_deck=room.id_deck WHERE id_room = '$roomAdmin'";
                        $listBay = mysqli_query($con, $sql);
                        $tempBay = array();
                        while ($bay = $listBay->fetch_array()) {
                            $tempBay[$bay[0]] = array($bay[0], $bay[1]);
                        }

                        $sql = "SELECT * FROM user WHERE id_room = '$roomAdmin'";
                        $listUser = mysqli_query($con, $sql);
                        $tempUser = array();
                        while ($user = $listUser->fetch_array()) {
                            array_push($tempUser, $user[0]);
                        }
                        if ($listUser->num_rows <= 0) {
                            echo 'Tidak ada user';
                        }
                        foreach ($tempUser as $user) {
                            // echo $user;
                            echo "<label for='origin" . $user . "' class='form-label d-flex just' style='margin-top: 10px;width: 30rem'>" . $user . "</label>";
                            echo "<select class='custom-select' name='origin" . $user . "' style='width: 30rem;'>";
                            foreach ($tempBay as $bay) {
                                echo "<option value='" . $bay[1] . "'>" . $bay[1] . "</option>";
                            }
                            echo "</select>";
                        }
                        ?>
                        <label for="roomCode" class="form-label d-flex just">Jumlah Ronde</label>
                        <input type="number" class="form-control" id="roomCode" name="ronde" placeholder="Jumlah ronde">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="adminStart">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row m-5">
        <h1>
            Room ID
            <?php
            if (isset($_SESSION['username'])) {
                echo $_SESSION['roomID'];
            } else {
                echo $_SESSION['roomID_admin'];
            }
            ?>

        </h1>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Team Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Origin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['usernameADM'])) {
                    $room = $_SESSION['roomID_admin'];
                } else {
                    $room = $_SESSION['roomID'];
                }
                $sql = "SELECT * FROM user WHERE id_room = '$room' ";
                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_array($result)) {
                    if ($row[3] == 1) {
                        $val = 'Connected';
                    } else {
                        $val = 'Disconnected';
                    }

                    echo "<tr>
                        <td>$row[0]</td>
                        <td>$val</td>
                        <td>$row[5]</td>
                        </tr>
                    ";
                }
                ?>
            </tbody>
        </table>

        <?php
        $sql = "SELECT id_admin FROM room WHERE id_room= ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $_SESSION["roomID_admin"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if (isset($_SESSION['usernameADM'])) {
            if ($_SESSION['usernameADM'] == mysqli_fetch_array($result)[0]) {
                echo '<form method = "POST"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SetGame">Start</button>
                    <button name="swap" id="swap" class="btn btn-danger">Swap</button></form>';

                if (isset($_POST['adminStart'])) {
                    // print_r($_POST);
                    foreach ($tempUser as $user) {
                        $bayUser = $_POST['origin' . $user];
                        $sql = "UPDATE user SET origin = '$bayUser' WHERE team_name = '$user'";
                        mysqli_query($con, $sql);
                    }
                    $room = $_SESSION['roomID_admin'];
                    $value = 1;
                    $sql = "UPDATE room SET status=? WHERE id_room =?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("ii", $value, $room);
                    $stmt->execute();
                }

                if (isset($_POST['swap'])) {
                    $roomID = $_SESSION['roomID_admin'];
                    $sql = "SELECT * FROM user WHERE id_room = $roomID";
                    $result = mysqli_query($con, $sql);

                    // Logic Swap JANGAN DIRUBAH
                    $tempBay = [];
                    $tempName = [];
                    while ($row = mysqli_fetch_array($result)) {

                        array_push($tempBay, $row[2]);
                        array_push($tempName, $row[0]);
                    }

                    $length = count($tempBay) - 1;

                    $temp = $tempBay[0];
                    for ($i = 0; $i < $length; $i++) {
                        $tempBay[$i] = $tempBay[$i + 1];
                    }
                    $tempBay[$length] = $temp;

                    //

                    // Testing 
                    // echo "$tempName[0] ====== $tempBay[0]";
                    // echo "<br>";
                    // echo "$tempName[1] ====== $tempBay[1]";
                    // echo "<br>";
                    // echo "$tempName[2] ====== $tempBay[2]";
                    // echo "<br>";

                    // Proses memasukan ship ke user setelah swap
                    for ($i = 0; $i <= $length; $i++) {
                        $bay = $tempBay[$i];
                        $name = $tempName[$i];

                        $sql = "UPDATE user SET ship = '$bay' WHERE team_name = '$name'";
                        $result = mysqli_query($con, $sql);
                    }

                    // $sql = "SELECT ship FROM user WHERE team_name = 'Samuel'";
                    // $result = mysqli_query($con, $sql);
                    // $row = mysqli_fetch_array($result);
                    // $hasil1 = json_decode($row['ship']);

                    // $sql2 = "SELECT ship FROM user WHERE team_name = 'Vincentius'";
                    // $result2 = mysqli_query($con, $sql2);
                    // $row2 = mysqli_fetch_array($result2);
                    // $hasil2 = json_decode($row2['ship']);

                    // $swap1 = json_encode($hasil2);
                    // $swap2 = json_encode($hasil1);
                    // $sql3 = "UPDATE user SET ship = '$swap1' WHERE team_name = 'Samuel'";
                    // $sql4 = "UPDATE user SET ship = '$swap2' WHERE team_name = 'Vincentius'";
                    // $result3 = mysqli_query($con, $sql3);
                    // $result4 = mysqli_query($con, $sql4);


                    // $sql = "SELECT * FROM temp_container WHERE id_user = 'Vincentius'";
                    // $result = mysqli_query($con,$sql);

                    // $count = 0;
                    // while($row = mysqli_fetch_array($result)){
                    //     $count = $count + 1;
                    // }

                    // if($count > 0){
                    //     $pay = $count * 1500;

                    //     $sql = "SELECT * FROM user WHERE team_name = 'Vincentius'";
                    //     $result = mysqli_query($con,$sql);
                    //     $row = mysqli_fetch_array($result);

                    //     $revenue = $row[6];
                    //     $revenue = $revenue - $pay;

                    //     $sql = "UPDATE user SET revenue = '$revenue' WHERE team_name = 'Vincentius'";
                    //     $result = mysqli_query($con,$sql);
                    // }
                }
            }
        } else {
            echo '<div class="userCont" id="userCont"></div>';
        }
        ?>
        <script>
            setInterval(() => {
                $.ajax({
                    url: 'userWaitingRoomLogic.php',
                    method: 'POST',
                    success: function(temp) {
                        console.log(temp)
                        if (temp == 'sukses') {
                            window.location.href = 'game1.php';

                        } else {
                            $('#userCont').html('<h2>Waiting for Host To Start The Game</h2>');
                        }
                    }

                });
            }, 1000);
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>