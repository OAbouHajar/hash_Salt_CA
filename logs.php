<html>

<head>
    <title>Logs Page</title>
    <link rel='icon' type='image/gif' href='../images/logo.png' />
    <title>Page Title</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>



<?php
include 'db.inc.php';
include "functions/pageBase.php";

if (strtoupper($_SESSION['user']) == 'ADMIN' && $_SESSION['password'] == 'SAD_2019!') {
    echo "
<body>
<div class='container'>
<h1>This is the logs page</h1>
</div>
<table>
  <tr>
    <th>Attemp ID</th>
    <th>User Name Attempted</th>
    <th>IP Address Attempting</th>
    <th>log Status</th>
    </tr>
";

    ##display table of logs

    $sql = "SELECT * FROM logs";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>"  . $row["id"] . "</td>";
            echo "<td>"  . $row["userName"] . "</td>";
            echo "<td>" . $row["ipAdderss"] . "</td>";
            echo "<td>" . $row["describeLog"] . "</td>";
            echo "</tr>";
        }
        echo " </table>";
    } else {
        echo "NO DATA TO SHOW";
    }
    $con->close();
} else {
    echo "
<body>
<div class='container'>
<h1>This is the logs page Avilable only for admin users Sorry</h1>
</div>
";
}


?>

</body>

</html>