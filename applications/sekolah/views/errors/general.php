<?php $this->view('templates/header', ['page_title' => 'Error']); ?>

<main id="main-container">
    <div class="hero">
        <div class="hero-inner text-center">
            <div class="bg-body-extra-light">
                <div class="content content-full">
                    <div class="py-4">
                        <h1 class="display-1 fw-bolder text-danger">Error</h1>
                        <h2 class="h4 fw-normal text-muted mb-5">We are sorry but an unexpected error has occurred.</h2>
                        <div class="alert alert-danger">
                            <p class="mb-0"><?php echo htmlspecialchars($message); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content content-full text-muted fs-sm fw-medium">
                <a class="link-fx" href="/sekolah/dashboard">Go to Dashboard</a>
            </div>
        </div>
    </div>
</main>

<?php $this->view('templates/footer'); ?>
