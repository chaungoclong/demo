<button class="btn-success shadow"><strong id="h_box" style="font-size: 18px;"></strong></button><span class="badge">:</span>
<button class="btn-primary shadow"><strong id="i_box" style="font-size: 18px;"></strong></button><span class="badge">:</span>
<button class="btn-danger shadow"><strong id="s_box" style="font-size: 18px;"></strong></button>
<script>
function clock() {
let now = new Date();
// giờ
let hour = now.getHours();
if(parseInt(hour) < 10) {
hour = `0${hour}`;
}
$('#h_box').text(hour);
// phút
let minute = now.getMinutes();
if(parseInt(minute) < 10) {
minute = `0${minute}`;
}
$('#i_box').text(minute);

// giây
let second = now.getSeconds();
if(parseInt(second) < 10) {
second = `0${second}`;
}
$('#s_box').text(second);
}
$(function() {
setInterval(clock, 1000);
});
</script>