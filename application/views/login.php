<php $this->load->view('shared/header',array('title'=>$title,'showmenu'=>false)); ?>

    <figure class="logo logo-home"><img src="/img/logo-front.png" alt="FieldTrippr" /></figure>
    <nav>
        <a href="<?php echo $loginUrl; ?>"><button target="_blank" type="button" id="login" class="blue">Log In</button></a>
        <button id="about">About</button>
        <button id="credits">Credits</button>
    </nav>

    <script type="text/javascript">
      // document.getElementById('login').addEventListener('click', function(){
      //     window.location = '<?php echo $loginUrl ?>';
      //     return false;
      // });
    </script>
  </body>
</html>

<?php $this->load->view('shared/footer'); ?>
