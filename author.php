<?php

// Include.
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Author.class.php");

// Open and connect to author database.
$author = new Author($db_host, $db_user, $db_pass, $db_name);
$author->open();
$author->getAuthor();

// If 'add' POST set, add data to database.
if (isset($_POST['add'])) {
    //memanggil add
    $author->add($_POST);
    header("location:author.php");
}

// If 'id_hapus' GET set, delete data from database.
if (!empty($_GET['id_hapus'])) {
    //memanggil add
    $id = $_GET['id_hapus'];

    $author->delete($id);
    header("location:author.php");
}

// If 'id_edit' GET set, modify data author from "Pendatang baru" to "Senior" on database.
if (!empty($_GET['id_edit'])) {
    //memanggil add
    $id = $_GET['id_edit'];

    $author->statusAuthor($id);
    header("location:author.php");
}

// Prepare data from database.
$data = null; $no = 1;
while (list($id, $nama, $status) = $author->getResult()) {
    if ($status == 'Senior') {
        $data .= "<tr>
                <td>" . $no++ . "</td>
                <td>" . $nama . "</td>
                <td>" . $status . "</td>
                <td>
                <a href='author.php?id_hapus=" . $id . "' class='btn btn-danger''>Hapus</a>
                </td>
                </tr>";
    } else {
        $data .= "<tr>
                <td>" . $no++ . "</td>
                <td>" . $nama . "</td>
                <td>" . $status . "</td>
                <td>
                <a href='author.php?id_edit=" . $id .  "' class='btn btn-warning''>Edit</a>
                <a href='author.php?id_hapus=" . $id . "' class='btn btn-danger''>Hapus</a>
                </td>
                </tr>";
    }
}

// Close connection and start writing the template.
$author->close();
$tpl = new Template("templates/author.html");
$tpl->replace("DATA_TABEL", $data);
$tpl->write();
