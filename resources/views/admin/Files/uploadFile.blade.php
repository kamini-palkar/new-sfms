@extends('admin.common.main')

@section('containes')


<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
</div>
</div>
</div>
</div>
</div>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
<main class="py-4">
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

        <div class="toolbar" id="kt_toolbar">

            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">

                <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
                    data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
                    class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">


                    <div class="d-flex align-items-center gap-2 gap-lg-3">

                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">


                        </div>

                        <a style="display:none" href="../../demo1/dist/.html" class="btn btn-sm btn-primary"
                            data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create</a>
                    </div>
                </div>

                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                </div>
                <a style="display:none" href="../../demo1/dist/.html" class="btn btn-sm btn-primary"
                    data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create</a>
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-2 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            &nbsp;
                            <span>Upload Files</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">

                    <div class="col-xl-12">
                        <div class="card card-flush h-lg-100" id="kt_contacts_main">

                            <div style="display:none" class="card-header pt-7" id="kt_chat_contacts_header">

                                <div style="display:none" class="card-title">


                                </div>
                            </div>

                            <div class="card-body pt-5">
                                <form method="POST" id="form" action="{{route('upload-file')}}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-1 row-cols-lg-3">

                                        <div class="field_wrapper">
                                            <div class="col">
                                                <div class="fv-row mb-2">
                                                    <label class="fs-6 fw-bold form-label mt-3">
                                                        <span class="">FILE UPLOAD</span><span
                                                            style="color: red;">*</span>

                                                    </label>
                                                    <input type="file" name="name[]" id="organisation_code"
                                                        class="form-control" autocomplete="off"
                                                        oninput="removeBorderStyle(this)">
                                                    <a href="javascript:void(0);" class="add_button" title="Add"><img
                                                            src="/images/plus.png"
                                                            style="height:30px; width:30px;padding-top: 0px;padding-right: 1px;padding-bottom: 3px;padding-left: -13px;margin-top: -55px;margin-right: -26px;margin-left: 370px;"></a>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div style="float:right;">

                                        <div class="d-flex justify-content-end">

                                            <button type="reset" onclick="history.back()" id="cancel_btn"
                                                class="btn btn-outline-danger"
                                                style="margin-right:10px;">Cancel</button>
                                            <button type="submit" id="submit" data-kt-contacts-type="submit"
                                                class="btn btn-primary">
                                                <span class="indicator-label">Save</span>


                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    </div>
    </div>
    </div>


    <style>
        #organisation_code-error {
            color: red;
            padding-top: 15px;

        }

        #Errormsg {
            color: red;
            margin-top: 10px;

        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>



    <script type="text/javascript">
    $(document).ready(function() {
        var maxField = 10;
        var addButton = $('.add_button');
        var wrapper = $('.field_wrapper');
        var fieldHTML =
            '<div class="field_wrapper"><input type="file"  class="form-control"   name="name[]" value=""/ style="margin-left:13px;width:345px;"><a href="javascript:void(0);" class="remove_button"><img src="/images/minus.png"/  style="height:30px; width:30px;padding-top: 0px;padding-right: 1px;padding-bottom: 3px;padding-left: -13px;margin-top: -55px;margin-right: -56px;margin-left: 385px;"></a></div>';
        var x = 1;

        //Once add button is clicked
        $(addButton).click(function() {
           
            if (x < maxField) {
                x++; 
                $(wrapper).append(fieldHTML); 
            }
        });

       
        $(wrapper).on('click', '.remove_button', function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });
    });
</script>





    @endsection