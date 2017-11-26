<?php
$this->title = 'OCR 识别测试';
$this->params['breadcrumbs'][] = ['label' => '运营工具', 'url' => ['index']];
$this->registerCssFile('/js/lib/upload/fileinput.min.css');
?>
<style>
    pre {
        padding: 5px; margin: 5px;
        border: none;
        background-color: transparent;
    }
    .string { color: green; }
    .number { color: darkorange; }
    .boolean { color: blue; }
    .null { color: magenta; }
    .key { color: red; }
    #editable img{
        height:288px;
        width:300px;
    }
    .select-method li{
        height:40px;
        line-height:40px;
        cursor: pointer;
    }
    .select-method li.active{
        color: red;
    }
</style>
<div class="box box-primary">
    <div class="box-body">
        <div class="col-md-6" id="ocr_left">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-md-4 control-label">请选择要识别的类型</label>
                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="ocr_type" id="isInsert" checked="checked" value="2" />
                            文字识别
                            <span class="radio-label">
                                    <label class="radio" for="vin"></label>
                                </span>
                        </label>
                      <label class="radio-inline">
                           <input type="radio" name="ocr_type" id="isUpdate" value="1" />车牌识别
                           <span class="radio-label">
                               <label class="radio" for="plate"></label>
                           </span>
                       </label>
                    </div>
                </div>
                <div class="form-group select-bar">
                    <ul class="col-md-4 select-method">
                        <li class="active">请选择要识别的文件</li>
                        <li>请复制要识别的文件</li>
                    </ul>
                    <!--                    <label for="" class="col-md-4 control-label">请选择要识别的文件</label>-->
                    <div class="col-md-8">
                        <form enctype="multipart/form-data">
                            <input type="file" name="file" class="file-loading" multiple id="txt_file" data-overwrite-initial="false">
                        </form>
                    </div>
                    <div class="col-md-8" id="editable" style="border: 1px solid #ddd; width: 300px ;height: 288px; outline: none;display: none;" contenteditable="true">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="baiduBox" style="background-color: rgb(236, 240, 244); display: none;">
            <span id="baiduOcr" style="display: none">ocr识别结果</span>
            <pre id="baiduResult">
            </pre>
        </div>
    </div>
</div>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/lib/upload/fileinput.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/lib/upload/zh.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/tool/ocr.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
