# ImageFileConvert

> image util to handle image file, canvas/image/blob/file translate

## [demo](https://kelen.github.io/ImageFileConvert/example/)

## USAGE

> copy dist imageFileConvert.umd.js for usage

### USE NPM

```
npm i image-file-convert

import ImageFileConvert from 'image-file-convert'
```

```html
<input type="file" accept="image/*" onchange="fileChange(event)">
<script src="../dist/imageFileConvert.umd.js"></script>
<script>
function fileChange(ev) {
  let file = ev.target.files[0];
  ImageFileConvert.getImageFileData(file, { width: 600, height: 800, cover: false }).then(({ blob, base64 }) => {
    let img = ImageFileConvert.blobToImage(blob);
    img.style.width = '300px';
    console.log(blob, base64.length)
    document.body.appendChild(img);
  });
}
</script>
```

## API

#### getImageFileData(file, option);

>  get image file input data, can compress size with option with and height

| 参数 | 类型 | 说明 |
| --- | --- | --- |
| file | file type | 文件类型 |
| option | object | 配置项 |
|  | width | 宽度 |
|  | height | 高度 |
|  | cover | 是否覆盖整个区域，默认false |

```javascript
function fileChange(ev) {
  let file = ev.target.files[0];
  ImageFileConvert.getImageFileData(file, { width: 300, height: 400, cover: true }).then(blob => {
    let img = ImageFileConvert.blobToImage(blob);
    document.body.appendChild(img);
  })
}
```

#### blobToImage(blob)

> blob translate to image, use for ImageFileData() result

| 参数 | 类型 | 说明 |
| --- | --- | --- |
| blob | blob | 二进制文件 |

```javascript
let img = ImageFileConvert.blobToImage(blob);
```


#### fileToCanvas(file, option);

> file translate to canvas and image, get canvas and image

| 参数 | 类型 | 说明 |
| --- | --- | --- |
| file | file type | 文件类型 |
| option | object | 配置项 |
|  | width | 宽度 |
|  | height | 高度 |
|  | cover | 是否覆盖整个区域，默认false |

```javascript
ImageFileConvert.fileToCanvas(file, { width: 400, height: 400 }).then(({ canvas, image }) => {
  document.body.appendChild(canvas);
})
```

#### fileToImage(file);

> file translate to image

| 参数 | 类型 | 说明 |
| --- | --- | --- |
| file | file type | 文件类型 |

```javascript
ImageFileConvert.fileToImage(file).then(img => {
    document.body.appendChild(img);
})
```

#### imageToCanvas(img);

>  image translate to canvas

| 参数 | 类型 | 说明 |
| --- | --- | --- |
| img | image element | 图片 |

```javascript
let canvas = ImageFileConvert.imageToCanvas(img);
```

#### canvasToImage(canvas);

> canvas tranlate to image, return promise

| 参数 | 类型 | 说明 |
| --- | --- | --- |
| canvas | canvas | canvas |


```javascript
ImageFileConvert.canvasToImage(cvs, 'image/png').then(canvas => {
  document.body.append(canvas);
})
```

#### canvasToFile(canvas);

> canvas translate to file

```javascript
let file = ImageFileConvert.canvasToFile(cvs);
```

### canvasToBase64(canvas, type = 'image/png', encoderOptions = '0.92');

> canvas to base64

```javascript
let base64 = ImageFileConvert.canvasToBase64(cvs);
```

#### imageToBase64(img);

> image translate to base64

```javascript
let base64 = ImageFileConvert.imageToBase64(img);
```

#### rotate(canvas, image, degree);

> rotate image by canvas and return canvas

| 参数 | 类型 | 说明 |
| --- | --- | --- |
| canvas | canvas | 需要绘制的canvs |
| image | img | img元素 |
| degree  | int | 角度 |

```javascript
ImageFileConvert.rotate(cvs, img, degree);
```
