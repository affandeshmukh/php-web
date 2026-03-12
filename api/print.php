<!DOCTYPE html>
<html>
<body>

<button onclick="connectPrinter()">Connect Printer</button>

<script>

function connectPrinter(){
    alert("Button working");

    if (!navigator.bluetooth) {
        alert("Bluetooth not supported");
        return;
    }

    navigator.bluetooth.requestDevice({
        acceptAllDevices:true
    })
    .then(device=>{
        alert("Device selected: " + device.name);
    })
    .catch(err=>{
        alert(err);
    });

}

</script>

</body>
</html>