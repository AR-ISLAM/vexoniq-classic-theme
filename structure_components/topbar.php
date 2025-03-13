<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="dashicons-div">
            <a href="https://www.facebook.com/profile.php?id=61569044551546" target="_blank"><span
                    class="dashicons dashicons-facebook"></span></a>
            <a href="https://www.youtube.com/@xinsheng-elec" target="_blank"><span
                    class="dashicons dashicons-youtube"></span></a>
            <a href="https://www.linkedin.com/company/xinsheng-electronic-co-ltd/" target="_blank"><span
                    class="dashicons dashicons-linkedin"></span></a>
        </div>
        <div class="location-translate-div">
            <div class="topbar-location" onclick="openModal()">
                <span class="dashicons dashicons-location"></span>
                <a class="location-text" href="javascript:void(0);" onclick="openModal()">Our Location</a>
            </div>
            <div class="language-switcher">
                <?php echo do_shortcode('[gtranslate]'); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="location-modal" class="location-modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <!-- Embed Google Map in iframe -->
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3395.7997272487164!2d120.0573930750754!3d31.666698539898913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35b47156653ae199%3A0x306d44ee91c4a914!2z5bi45bee5biC5paw55ub6Zu75Zmo5pyJ6ZmQ5YWs5Y-4!5e0!3m2!1szh-TW!2stw!4v1741232550765!5m2!1szh-TW!2stw"
            style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById("location-modal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("location-modal").style.display = "none";
    }

    // Close the modal if the user clicks anywhere outside the modal content
    window.onclick = function (event) {
        if (event.target == document.getElementById("location-modal")) {
            closeModal();
        }
    }
</script>