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
    <!-- Button trigger modal -->


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
                <div class="modal-body">
                    <form method="POST">
                        <?php
                        $roomAdmin = $_SESSION['roomID_admin'];
                        $sql = "SELECT bay.id_bay, bay.nama_bay, bay.detail_bay FROM bay INNER JOIN room ON bay.id_deck=room.id_deck WHERE id_room = '$roomAdmin'";
                        $listBay = mysqli_query($con, $sql);


                        $sql = "SELECT * FROM user WHERE id_room = '$room'";
                        $listUser = mysqli_query($con, $sql);
                        while ($user = mysqli_fetch_array($listUser)) {
                            echo "<label for='origin" . $user[0] . "' class='form-label d-flex just' style='margin-top: 10px;width: 30rem'>" . $user[0] . "</label>";
                            echo "<select class='custom-select' aria-label='Default select example' name='origin" . $user[0] . "' style='width: 30rem;'>";
                            while ($row = mysqli_fetch_array($listBay)) {
                                echo "<option value=$row[0]>$row[1]</option>";
                            }
                            echo "</select>";
                        }
                        ?>
                        <label for="roomCode" class="form-label d-flex just">Jumlah Ronde</label>
                        <input type="number" class="form-control" id="roomCode" name="ronde" placeholder="Jumlah ronde">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name="adminStart">Save changes</button>
                </div>
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

                </h1>
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
                echo '<form method = "POST"><button name="adminStart" id="adminStart" class="btn btn-primary">Start</button>
                    <button name="swap" id="swap" class="btn btn-danger">Swap</button></form>';
                if (isset($_POST['adminStart'])) {
                    $room = $_SESSION['roomID_admin'];
                    $value = 1;
                    $sql = "UPDATE room SET status=? WHERE id_room =?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("ii", $value, $room);
                    $stmt->execute();
                }
                if (isset($_POST['swap'])) {
                    $sql = "SELECT ship FRom user WHERE team_name = 'Actonoi'";
                    $result = mysqli_query($con, $sql);
                    $row = mysqli_fetch_array($result);
                    $hasil1 = json_decode($row['ship']);

                    $sql2 = "SELECT ship FRom user WHERE team_name = 'Vincentius'";
                    $result2 = mysqli_query($con, $sql2);
                    $row2 = mysqli_fetch_array($result2);
                    $hasil2 = json_decode($row2['ship']);

                    $swap1 = json_encode($hasil2);
                    $swap2 = json_encode($hasil1);
                    $sql3 = "UPDATE user SET ship = '$swap1' WHERE team_name = 'Actonoi'";
                    $sql4 = "UPDATE user SET ship = '$swap2' WHERE team_name = 'Vincentius'";
                    $result3 = mysqli_query($con, $sql3);
                    $result4 = mysqli_query($con, $sql4);
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