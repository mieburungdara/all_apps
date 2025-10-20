<div class="hero-static col-md-6 d-flex align-items-center bg-body-extra-light">
    <div class="p-3 w-100">
        <div class="mb-3 text-center">
            <a class="link-fx fw-bold" href="#">
                <i class="fa fa-fire"></i>
                <span class="fs-4 text-body-color">dash</span><span class="fs-4 text-primary">mix</span>
            </a>
            <p class="text-uppercase fw-bold fs-sm text-muted">Installation in Progress (Step 6/6)</p>
        </div>
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-7">
                
                <div class="progress push" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="install-progress-bar-container">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="install-progress-bar" style="width: 0%;"></div>
                </div>

                <div id="install-log" class="form-control" style="height: 250px; overflow-y: scroll; background-color: #222; color: #eee; font-family: monospace; font-size: 0.8rem;">
                    Waiting to start...
                </div>

                <div class="mt-4 text-center" id="finish-button-container" style="display: none;">
                    <a href="#" id="finish-link" class="btn btn-lg btn-hero btn-success">
                        <i class="fa fa-fw fa-check-circle opacity-50 me-1"></i> Go to Login
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.getElementById('install-progress-bar');
    const logContainer = document.getElementById('install-log');
    const finishButtonContainer = document.getElementById('finish-button-container');
    const finishLink = document.getElementById('finish-link');

    function addLog(message) {
        logContainer.innerHTML += "\n" + message;
        logContainer.scrollTop = logContainer.scrollHeight;
    }

    function setProgress(value) {
        progressBar.style.width = value + '%';
        progressBar.setAttribute('aria-valuenow', value);
    }

    addLog("Starting installation...");
    setProgress(5);

    fetch('/sekolah/installer/run_installation', {
        method: 'POST',
    })
    .then(response => {
        const reader = response.body.getReader();
        return new ReadableStream({
            start(controller) {
                function push() {
                    reader.read().then(({ done, value }) => {
                        if (done) {
                            controller.close();
                            return;
                        }
                        controller.enqueue(value);
                        push();
                    });
                }
                push();
            }
        });
    })
    .then(stream => new Response(stream))
    .then(response => response.text())
    .then(text => {
        // Process the streamed chunks
        const chunks = text.split('--CHUNK--');
        chunks.forEach(chunk => {
            if (chunk.trim() === '') return;
            try {
                const data = JSON.parse(chunk);
                addLog(data.message);
                setProgress(data.progress);

                if (data.status === 'complete') {
                    addLog("\nInstallation Finished!");
                    finishLink.href = data.redirect_url;
                    finishButtonContainer.style.display = 'block';
                }
            } catch (e) {
                addLog("Error processing update: " + chunk);
            }
        });
    })
    .catch(err => {
        addLog("\n--- INSTALLATION FAILED ---");
        addLog(err);
        progressBar.classList.remove('bg-success');
        progressBar.classList.add('bg-danger');
    });
});
</script>
