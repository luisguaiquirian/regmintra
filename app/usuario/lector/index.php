<?
	if(!isset($_SESSION))
	{
		session_start();
	}	

	include_once $_SESSION['base_url'].'partials/header.php';
?>	
   <style>
.line {
  width: 220px;
  height: 2px;
  margin-top: 100px;
  background: red;
  position :absolute;
  animation: mymove 3s infinite;
}
.line1 {
  width: 220px;
  height: 2px;
  margin-top: 100px;
  background: red;
  position :absolute;
  animation: mymove1 3s infinite;
}
/* Safari 4.0 - 8.0 */
@keyframes mymove {
  from {top: 0px;}
  to {top: 290px;}
}

/* Standard syntax */
@keyframes mymove1 {
  from {top: 290px;}
  to {top: 0px;}
}
</style>
    
       	
       	<div id="video" style="display: flex; justify-content: center;align-items: center">
		<video id="preview1" class="" style="height: 290px; width: 220px"></video>
    	<div class="line" id="line" style="visibility: hidden"></div>
        <div class="line1" id="line1" style="visibility: hidden"></div>
	    </div>
	

 
 
  <div id="modal_cuenta" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: black; color: white;">
            <h4 class="modal-title">Aviso!<i class="fa fa-warning"></i></h4>
        </div>
        <div class="modal-body">
          <h4 class="text-center">Debe activar la cámara de su dispositivo</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div id="modal_result" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: black; color: white;">
            <h4 class="modal-title">Aviso!<i class="fa fa-warning"></i></h4>
        </div>
        <div class="modal-body">
          <div id="div_result" class="text-center">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>


<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>-->
<!--<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>-->
<script type="text/javascript" src="<?= $_SESSION['base_url1'].'assets/js/adapter.js' ?>?"></script>
<script type="text/javascript" src="<?= $_SESSION['base_url1'].'assets/js/instascan.min.js' ?>?"></script>

<script type="text/javascript">
     
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
 
    if (/android/i.test(userAgent)) {
    document.getElementById("video").style.transform="scaleX(-1)";
    document.getElementById("preview1").style.border="solid black"
    document.getElementById("preview1").style.marginTop="100px"
    document.getElementById("line").style.visibility = "visible";
    document.getElementById("line1").style.visibility = "visible";

    }
   
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
    document.getElementById("video").style.transform="scaleX(-1)";
    document.getElementById("preview1").style.border="solid black"
    document.getElementById("preview1").style.marginTop="100px"
    document.getElementById("line").style.visibility = "visible";
    document.getElementById("line1").style.visibility = "visible";
    }

    
    function isUrl (string) {
    	var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/ 
    	return regexp.test(string);
    }

    let scanner = new Instascan.Scanner({ video: document.getElementById('preview1') });

    scanner.addListener('scan', function (content) {
      if(isUrl(content)){
      	window.open(content,"_blank")
      }else{
        content = content.split(",")
        let ver_mas = ""
        let data = ""
        
        content.forEach((i,e) => {
          if(isUrl(i)){
            ver_mas = `<a href="${i}" target="_blank" class="btn btn-primary">Ir al sitio Web</a>`
          }else{
            data+= `<h3>${i}</h3><br/>`
          }
        })

        data = data + ver_mas

        $('#div_result').html(data)

        $('#modal_result').modal('show')

      }
    });

    Instascan.Camera.getCameras().then(function (cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        $('#modal_cuenta').modal('show')
      }
    }).catch(function (e) {
      console.error(e);
    });

</script>
