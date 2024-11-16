<?php
if(isset($_SESSION['mensaje'])){ ?>
    <script>
        Swal.fire({
            position: "top-center",
            icon: "<?php echo $_SESSION['icono']?>",
            title: "<?php echo $_SESSION['titulo'] ?>",
            text: "<?php echo $_SESSION['mensaje']?>",
            showConfirmButton: false,
            timer: 3000
        });
    </script>
<?php unset($_SESSION['mensaje']);
}
?>