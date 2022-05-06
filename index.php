<?php

/* Saya [Muhammad Satria Ramadhani - 2005128] mengerjakan evaluasi [Latihan
Praktikum 11] dalam mata kuliah [Desain dan Pemrograman Berorientasi Objek]
untuk keberkahan-Nya, maka saya tidak melakukan kecurangan seperti yang
telah dispesifikasikan. Aamiin. */

// Include.
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Buku.class.php");
include("includes/Author.class.php");

// Open and connect to book and author database. 
$buku = new Buku($db_host, $db_user, $db_pass, $db_name);
$author = new Author($db_host, $db_user, $db_pass, $db_name);
$buku->open();
$author->open();
$buku->getBuku();
$author->getAuthor();

$status = false;
$alert = null;

// If 'add' POST set, add data to database.
if (isset($_POST['add'])) {
    $buku->add($_POST);
    header("location:index.php");
}

// If 'id_hapus' GET set, delete data from database.
if (!empty($_GET['id_hapus'])) {
    $id = $_GET['id_hapus'];

    $buku->delete($id);
    header("location:index.php");
}

// If 'id_edit' GET set, modify data book from "-" to "Best Seller" on database.
if (!empty($_GET['id_edit'])) {
    $id = $_GET['id_edit'];

    $buku->statusBuku($id);
    header("location:index.php");
}

// Prepare data from database, based on their status.
$data = null; $dataAuthor = null; $no = 1;
while (list($id, $judul, $penerbit, $deskripsi, $status, $id_author) = $buku->getResult()) {
    if ($status == "Best Seller") {
        $data .= "<tr>
            <td>" . $no++ . "</td>
            <td>" . $judul . "</td>
            <td>" . $penerbit . "</td>
            <td>" . $deskripsi . "</td>
            <td>" . $status . "</td>
            <td>" . $id_author . "</td>
            <td>
            <a href='index.php?id_hapus=" . $id . "' class='btn btn-danger' '>Hapus</a>
            </td>
            </tr>";
    }
    else {
        $data .= "<tr>
            <td>" . $no++ . "</td>
            <td>" . $judul . "</td>
            <td>" . $penerbit . "</td>
            <td>" . $deskripsi . "</td>
            <td>" . $status . "</td>
            <td>" . $id_author . "</td>
            <td>
            <a href='index.php?id_edit=" . $id .  "' class='btn btn-warning' '>Edit</a>
            <a href='index.php?id_hapus=" . $id . "' class='btn btn-danger' '>Hapus</a>
            </td>
            </tr>";
    }
}

// Write option value for author list.
while (list($id, $nama, $status) = $author->getResult()) {
    $dataAuthor .= "<option value='".$id."'>".$nama."</option>
                ";
}

// Close connection and start writing the template.
$author->close();
$buku->close();
$tpl = new Template("templates/index.html");
$tpl->replace("OPTION", $dataAuthor);
$tpl->replace("DATA_TABEL", $data);
$tpl->write();
