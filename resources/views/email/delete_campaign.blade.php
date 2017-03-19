<!DOCTYPE html>
<html>
<head>
    <title>{{ trans('email.delete_campaign.title') }}</title>
    <style>
        .content {
            background: darkcyan;
            padding: 50px;
        }
        .delete_campaign {
            display: block;
            margin: 50px auto;
            background: white;
            max-width: 500px;
            padding: 15px;
            box-shadow: 5px 5px 2px black;
        }
        .delete_campaign .heding {
            text-align: center;
        }
        .delete_campaign .body {
            padding:15px;
        }
        .hr-heading-body {
            width: 200px;
            border: 1px solid black;
        }
        .hr-body-footer {
            border: 1px solid darkcyan;
        }
        .box-info .head {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="delete_campaign">
        <div class="heding">
            <h2><b>{{ trans('email.delete_campaign.head') }}</b></h2>
        </div>
        <hr class="hr-heading-body">
        <div class="body">
        <p> {{ trans('campaign.name') }} : {{ $campaignInfo['name'] }} </p>
        <br>
        <p> {{ trans('campaign.description') }} : {{ $campaignInfo['description'] }} </p>
        <br>
        <p> {{ trans('campaign.address') }} : {{ $campaignInfo['address'] }} </p>
        <br>
        </div>
        <hr class="hr-body-footer">
    </div>
</div>
</body>
</html>
