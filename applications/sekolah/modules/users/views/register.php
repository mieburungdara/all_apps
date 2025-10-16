<div class="p-4">
    <div class="mb-4">
        <h1 class="fs-2 fw-bold mb-1">Create Account</h1>
        <p class="fw-medium text-muted">
            Get your access today in one easy step
        </p>
    </div>
    <form action="/sekolah/users/register" method="POST">
        <div class="mb-4">
            <input type="text" class="form-control form-control-lg" name="nama" placeholder="Name">
        </div>
        <div class="mb-4">
            <input type="email" class="form-control form-control-lg" name="email" placeholder="Email">
        </div>
        <div class="mb-4">
            <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a class="text-muted fs-sm fw-medium d-block d-lg-inline-block mb-1" href="/sekolah/users/login">
                    Already have an account?
                </a>
            </div>
            <div>
                <button type="submit" class="btn btn-lg btn-alt-success">
                    <i class="fa fa-fw fa-plus me-1 opacity-50"></i> Sign Up
                </button>
            </div>
        </div>
    </form>
</div>
