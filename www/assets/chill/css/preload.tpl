<div class="preloader">
  <div class="preloader__image">
    <img src="/assets/chill/images/logo.png">
  </div>
</div>
<script>
  window.onload = function () {
    document.body.classList.add('loaded_hiding');
    window.setTimeout(function () {
      document.body.classList.add('loaded');
      document.body.classList.remove('loaded_hiding');
    }, 500);
  }
</script>

