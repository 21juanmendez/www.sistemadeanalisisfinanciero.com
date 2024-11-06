<?php
if (isset($_SESSION['mensaje'])) { ?>
    <script>
        Swal.fire({
            position: "center",
            icon: "<?php echo $_SESSION['icono']; ?>",
            title: "<?php echo $_SESSION['title']; ?>",
            text: "<?php echo $_SESSION['mensaje']; ?>",
            showConfirmButton: false,
            timer: 3000,
            scrollbarPadding: false
        });
    </script>
    <?php unset($_SESSION['mensaje']);
} ?>
