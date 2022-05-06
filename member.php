<?php

// Include.
include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Member.class.php");

// Open and connect to member database. 
$member = new Member($db_host, $db_user, $db_pass, $db_name);
$member->open();

// Prepare input condition.
$nim_input = null;
$name_input = null;
$program_input = null;
$button = null;

/* I think this is the trickiest method, yet beautiful because I learn to
   modify data from database without changing page.
   In a nutshell, if condition is editing then add value to every input form
   and "lock" the NIM one. If condition is adding, just use regular input.
   Don't forget to change the button condition, so we did use the GET to get
   value and POST to update data. */

// Prepare a condition if the user want to edit.
$id_update = null;
if (!empty($_GET['id_edit']))
{
    $id_update = $_GET['id_edit'];

    $member->getData($id_update);
    while (list($nim, $nama, $jurusan) = $member->getResult())
    {
        $nim_input .= "<input type = 'text' class = 'form-control' name = 'nim' value = '{$id_update}' readonly='true' />";
        $name_input .= "<input type = 'text' class = 'form-control' name = 'nama' value = '{$nama}' required />";
        $program_input .= "<input type = 'text' class = 'form-control' name = 'program' value = '{$jurusan}' required />";
        $button .= "<button type = 'submit' name = 'update' class = 'btn btn-primary mt-3'>Update</button>";
    }
}
// Prepare a condition if the user want to add data.
else
{
    $nim_input .= "<input type = 'text' class = 'form-control' name = 'nim' required />";
    $name_input .= "<input type='text' class='form-control' name='nama' required />";
    $program_input .= "<input type = 'text' class = 'form-control' name = 'program' required />";
    $button .= "<button type = 'submit' name = 'add' class = 'btn btn-primary mt-3'>Add</button>";
}

// If 'id_hapus' GET set, delete data from database.
if (!empty($_GET['id_hapus']))
{
    //memanggil add
    $id = $_GET['id_hapus'];

    $member->delete($id);
    header("location:member.php");
}

// If 'add' POST set, add data to database.
if (isset($_POST['add']))
{
    //memanggil add
    $member->add($_POST['nim'], $_POST['nama'], $_POST['program']);
    header("location:member.php");
}

// If 'update' POST set, add data to database.
if (isset($_POST['update']))
{
    //memanggil add
    $member->update($_POST['nim'], $_POST['nama'], $_POST['program']);
    header("location:member.php");
}


// Prepare data from database.
$data = null; $dataMember = null; $no = 1;
$member->getMember();
while (list($nim, $nama, $jurusan) = $member->getResult())
{
    $dataMember .= "<tr>
                    <td>" . $no++ . "</td>
                    <td>" . $nim . "</td>
                    <td>" . $nama . "</td>
                    <td>" . $jurusan . "</td>
                    <td>
                    <a href='member.php?id_edit=" . $nim .  "' class='btn btn-warning' '>Edit</a>
                    <a href='member.php?id_hapus=" . $nim . "' class='btn btn-danger' '>Hapus</a>
                    </td>
                    </tr>";
}


// Close connection and start writing the template.
$member->close();
$tpl = new Template("templates/member.html");
$tpl->replace("DATA_TABEL", $dataMember);
$tpl->replace("INPUT_NIM", $nim_input);
$tpl->replace("INPUT_NAMA", $name_input);
$tpl->replace("INPUT_JURUSAN", $program_input);
$tpl->replace("INPUT_BUTTON", $button);
$tpl->write();
