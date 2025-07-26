@extends('admin.layout')

@section('content')
    <div class="container org-dashboards">
        <h5 class="mt-5">Project Dashboards</h5>
        <div class="d-flex flex-wrap justify-content-center align-items-center justify-content-md-start">
            @foreach($clients as $client)
                <div class="card m-3 position-relative z-0" style="width: 18rem">
                    <img src="<?= $client['logo'] ?>" class="card-img-top img-thumbnail" alt="clogo"
                        style="height: 240px; width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $client['name'] ?></h5>
                        <p class="card-text text-truncate" style="max-width: 100%;" data-bs-toggle="tooltip"
                            title="<?= htmlspecialchars($client['description']) ?>">
                            <?= htmlspecialchars($client['description']) ?>
                        </p>
                        <a href="<?= $client['path'] ?>" class="btn btn-light">
                            Open
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="container webapp">
        <h5 class="mt-5">Web Applications</h5>
        <div class="d-flex flex-wrap justify-content-center align-items-center justify-content-md-start">
            @foreach($apps as $app)
                <div class="card m-3 position-relative z-0" style="width: 18rem">
                    <img src="<?= $app['logo'] ?>" class="card-img-top img-thumbnail" alt="clogo"
                        style="height: 240px; width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $app['name'] ?></h5>
                        <p class="card-text text-truncate" style="max-width: 100%;" data-bs-toggle="tooltip"
                            title="<?= htmlspecialchars($app['description']) ?>">
                            <?= htmlspecialchars($app['description']) ?>
                        </p>
                        <a href="<?= $app['path'] ?>" target="_blank" class="btn btn-light">
                            Open
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection