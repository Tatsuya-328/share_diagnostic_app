<div class="card card-body text-white bg-secondary mb-3">
    <form id="demo-form2" data-parsley-validate class="form-horizontal">
        <h4 class="card-title">検索</h4>
        <div class="row">
            @yield('search_content')
            <div class="col-lg-1 col-md-6 col-12 text-right mt-4 my-3">
                <button type="submit" class="btn btn-success">検索</button>
            </div>
        </div>
    </form>
</div>
