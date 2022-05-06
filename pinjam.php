<?php

// Include.
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Buku.class.php");
include("includes/Member.class.php");
include("includes/Peminjaman.class.php");

// Open and connect to member database. 
$member = new Member($db_host, $db_user, $db_pass, $db_name);
$member->open(); $member->getMember();

// Write option value for member list.
$dataMember = null;
while (list($nim, $nama, $jurusan) = $member->getResult())
{
    $dataMember .= "<option value = {$nim}>" . "{$nim} - {$nama}" . "</option>";
}

// Close member connection.
$member->close();

// Open and connect to book database.
$book = new Buku($db_host, $db_user, $db_pass, $db_name);
$book->open(); $book->getBuku();

// Write option value for book list.
$dataBuku = null;
while (list($id, $judul, $penerbit, $deksripsi, $status, $author) = $book->getResult())
{
    $dataBuku .= "<option value = {$id}>" . "{$judul}" . "</option>";
}

// Close book connection.
$book->close();

// Open and connect to borrow database.
$pinjam = new Peminjaman($db_host, $db_user, $db_pass, $db_name);
$pinjam->open(); $pinjam->getData();

// If 'add' POST set, add data to database.
if (isset($_POST['add'])) {
    $pinjam->add($_POST['member'], $_POST['book']);
    header("location:pinjam.php");
}

// If 'id_hapus' GET set, delete data from database.
if (!empty($_GET['id_hapus'])) {
    $id = $_GET['id_hapus'];

    $pinjam->delete($id);
    header("location:pinjam.php");
}

// If 'id_edit' GET set, modify status from "0" (Dipinjam) to "1" (Dikembalikan) on database.
if (!empty($_GET['id_edit'])) {
    $id = $_GET['id_edit'];

    $pinjam->update($id);
    header("location:pinjam.php");
}

// Prepare data from database, based on their status.
$dataPeminjaman = null; $no = 1;
while (list($id, $nim, $nama, $jurusan, $buku, $status) = $pinjam->getResult())
{
    if($status == '0')
    {
        $dataPeminjaman .= "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . $nim . "</td>
                            <td>" . $nama . "</td>
                            <td>" . $jurusan . "</td>
                            <td>" . $buku . "</td>
                            <td>" . "Dipinjam" . "</td>
                            <td>
                            <a href='pinjam.php?id_edit=" . $id .  "' class='btn btn-success' '>Selesai</a>
                            <a href='pinjam.php?id_hapus=" . $id . "' class='btn btn-danger' '>Hapus</a>
                            </td>
                            </tr>";
    }
    else
    {
        $dataPeminjaman .= "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . $nim . "</td>
                            <td>" . $nama . "</td>
                            <td>" . $jurusan . "</td>
                            <td>" . $buku . "</td>
                            <td>" . "Dikembalikan" . "</td>
                            <td>
                            <a href='pinjam.php?id_hapus=" . $id . "' class='btn btn-danger' '>Hapus</a>
                            </td>
                            </tr>";
    }
    
}

// Close connection and start writing the template.
$tpl = new Template("templates/pinjam.html");
$tpl->replace("OPTION_MEMBER", $dataMember);
$tpl->replace("OPTION_BUKU", $dataBuku);
$tpl->replace("DATA_TABEL", $dataPeminjaman);
$tpl->write();
