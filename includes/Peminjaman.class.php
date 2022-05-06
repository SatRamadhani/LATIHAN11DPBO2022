<?php

    class Peminjaman extends DB
    {
        function add($nim, $book)
        {
            $query = "INSERT INTO peminjaman VALUES ('', '$nim', '$book', '0')";
            return $this->execute($query);
        }

        function delete($id)
        {
            $query = "DELETE FROM peminjaman WHERE id = '$id'";
            return $this->execute($query);
        }

        function getPeminjaman()
        {
            $query = "SELECT * FROM peminjaman";
            return $this->execute($query);
        }

        function getData()
        {
            $query = "SELECT peminjaman.id, peminjaman.nim, member.nama,
                      member.jurusan, buku.judul_buku, peminjaman.status FROM
                      peminjaman INNER JOIN member ON member.nim = peminjaman.nim
                      INNER JOIN buku ON peminjaman.id_buku = buku.id_buku ORDER BY
                      peminjaman.id";
            return $this->execute($query);
        }

        function update($id)
        {
            $query = "UPDATE peminjaman SET status = '1' WHERE id = '$id'";
            return $this->execute($query);
        }
    }

?>