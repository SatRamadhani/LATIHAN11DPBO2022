<?php

class Member extends DB
{
    function add($nim, $name, $program)
    {
        $query = "INSERT INTO member VALUES ('$nim', '$name', '$program')";
        return $this->execute($query);
    }

    function delete($id)
    {
        $query = "DELETE FROM member WHERE nim = '$id'";
        echo $query;
        return $this->execute($query);
    }

    function getData($id)
    {
        $query = "SELECT * FROM member WHERE nim = '$id'";
        return $this->execute($query);
    }

    function getMember()
    {
        $query = "SELECT * FROM member";
        return $this->execute($query);
    }

    function update($nim, $name, $program)
    {
        $query = "UPDATE member SET nama = '$name', jurusan = '$program' where nim = '$nim'";
        return $this->execute($query);
    }
}

?>