const CODE = {
  ['NOT_IMAGE']: -1,
  ['CANVAS_TO_IMAGE_ERROR']: -2,
  ['GET_IMAGE_FILE_ERROR']: -3
};

if (typeof Object.assign !== 'function') {
  // Must be writable: true, enumerable: false, configurable: true
  Object.defineProperty(Object, "assign", {
    value: function assign(target, varArgs) { // .length of function is 2
      'use strict';
      if (target == null) { // TypeError if undefined or null
        throw new TypeError('Cannot convert undefined or null to object');
      }
      var to = Object(target);
      for (var index = 1; index < arguments.length; index++) {
        var nextSource = arguments[index];
        if (nextSource != null) { // Skip over if undefined or null
          for (var nextKey in nextSource) {
            // Avoid bugs when hasOwnProperty is shadowed
            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
              to[nextKey] = nextSource[nextKey];
            }
          }
        }
      }
      return to;
    },
    writable: true,
    configurable: true
  });
}

const ImageFileConvert = {
  // from http://stackoverflow.com/a/32490603
  getOrientation: function (file, callback) {
    var reader = new FileReader();
    reader.onload = function (event) {
      var view = new DataView(event.target.result);

      if (view.getUint16(0, false) != 0xFFD8) return callback(-2);

      var length = view.byteLength,
        offset = 2;

      while (offset < length) {
        var marker = view.getUint16(offset, false);
        offset += 2;

        if (marker == 0xFFE1) {
          if (view.getUint32(offset += 2, false) != 0x45786966) {
            return callback(-1);
          }
          var little = view.getUint16(offset += 6, false) == 0x4949;
          offset += view.getUint32(offset + 4, little);
          var tags = view.getUint16(offset, little);
          offset += 2;

          for (var i = 0; i < tags; i++)
            if (view.getUint16(offset + (i * 12), little) == 0x0112)
              return callback(view.getUint16(offset + (i * 12) + 8, little));
        }
        else if ((marker & 0xFF00) != 0xFF00) break;
        else offset += view.getUint16(offset, false);
      }
      return callback(-1);
    };

    reader.readAsArrayBuffer(file.slice(0, 64 * 1024));
  },
  /**
   * convert base64 to blob
   * @param urlData
   */
  base64UrlToBlob(urlData) {
    var bytes = window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte
    //处理异常,将ascii码小于0的转换为大于0
    var ab = new ArrayBuffer(bytes.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < bytes.length; i++) {
      ia[i] = bytes.charCodeAt(i);
    }
    return new Blob([ab], { type: 'image/png' });
  },
  /**
   * dataUrl to file
   * new File 不兼容 ie 和 safari
   */
  dataURLtoFile(dataurl, filename = 'file') {
    let arr = dataurl.split(','),
      mime = arr[0].match(/:(.*?);/)[1],
      suffix = mime.split('/')[1],
      bstr = atob(arr[1]),
      n = bstr.length,
      u8arr = new Uint8Array(n);
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n)
    }
    return new File([u8arr], `${filename}.${suffix}`, { type: mime });
  },
  /**
   * data url to blob
   * @param dataURI
   */
  dataURLtoBlob(dataURI) {
    let hasBlobConstructor =
      window.Blob &&
      (function () {
        try {
          return Boolean(new Blob())
        } catch (e) {
          return false
        }
      })();
    let hasArrayBufferViewSupport =
      hasBlobConstructor &&
      window.Uint8Array &&
      (function () {
        try {
          return new Blob([new Uint8Array(100)]).size === 100
        } catch (e) {
          return false
        }
      })();
    let BlobBuilder =
      window.BlobBuilder ||
      window.WebKitBlobBuilder ||
      window.MozBlobBuilder ||
      window.MSBlobBuilder;
    let dataURIPattern = /^data:((.*?)(;charset=.*?)?)(;base64)?,/;
    let matches,
      mediaType,
      isBase64,
      dataString,
      byteString,
      arrayBuffer,
      intArray,
      i,
      bb;
    // Parse the dataURI components as per RFC 2397
    matches = dataURI.match(dataURIPattern)
    if (!matches) {
      throw new Error('invalid data URI')
    }
    // Default to text/plain;charset=US-ASCII
    mediaType = matches[2]
      ? matches[1]
      : 'text/plain' + (matches[3] || ';charset=US-ASCII')
    isBase64 = !!matches[4]
    dataString = dataURI.slice(matches[0].length)
    if (isBase64) {
      // Convert base64 to raw binary data held in a string:
      byteString = atob(dataString)
    } else {
      // Convert base64/URLEncoded data component to raw binary:
      byteString = decodeURIComponent(dataString)
    }
    // Write the bytes of the string to an ArrayBuffer:
    arrayBuffer = new ArrayBuffer(byteString.length)
    intArray = new Uint8Array(arrayBuffer)
    for (i = 0; i < byteString.length; i += 1) {
      intArray[i] = byteString.charCodeAt(i)
    }
    // Write the ArrayBuffer (or ArrayBufferView) to a blob:
    if (hasBlobConstructor) {
      return new Blob([hasArrayBufferViewSupport ? intArray : arrayBuffer], {
        type: mediaType
      })
    }
    bb = new BlobBuilder();
    bb.append(arrayBuffer);
    return bb.getBlob(mediaType)
  },
  /**
   * blob to image
   * @param blob
   */
  blobToImage(blob) {
    let img = new Image();
    img.src = URL.createObjectURL(blob);
    return img;
  },
  /**
   * get image file data
   * @param file
   * @param size
   * @param options
   * @returns {Promise<any>}
   */
  getImageFileData(file, options = {}) {
    let me = this, b64 = null, img = null;

    function transCallback(blob, resolve, reject) {
      // to base64
      img = me.blobToImage(blob)

      let blobRet = Object.assign(blob, {
        name: file.name,
        lastModified: file.lastModified,
        lastModifiedDate: file.lastModifiedDate,
        webkitRelativePath: file.webkitRelativePath
      })

      img.onload = function() {
        const base64 = me.imageToBase64(img)
        resolve({ blob: blobRet, base64: base64 });
      } 
      img.onerror = function() {
        resolve({ blob: blobRet, base64: null })
      }
    }

    return new Promise((resolve, reject) => {
      me.getOrientation(file, function (orientation) {
        // 旋转90度
        me.fileToCanvas(file, options).then(({ canvas, image }) => {
          if (orientation === 6)
            ImageFileConvert.rotate(canvas, image, 90);
          if (canvas.toBlob) {
            canvas.toBlob(function (blob) {
              transCallback(blob, resolve, reject)
            });
          } else {
            // fallback ios10
            let dataUrl = canvas.toDataURL('image/png');
            let blob = me.dataURLtoBlob(dataUrl);
            transCallback(blob, resolve, reject)
          }
        }, err => {
          reject({
            code: CODE.GET_IMAGE_FILE_ERROR,
            msg: '获取图片资源失败'
          });
        });
      })
    });
  },
  /**
   * image to canvas
   * @param img
   * @returns {HTMLElement}
   */
  imageToCanvas(img) {
    const canvas = document.createElement('canvas'),
      ctx = canvas.getContext('2d');
    canvas.width = img.width;
    canvas.height = img.height;
    ctx.drawImage(img, 0, 0);
    return canvas;
  },
  /**
   * canvas to base64
   * https://developer.mozilla.org/zh-CN/docs/Web/API/HTMLCanvasElement/toDataURL
   * @param {*} canvas 
   */
  canvasToBase64(canvas, type = 'image/png', encoderOptions = '0.92') {
    return canvas.toDataURL(type, encoderOptions);
  },
  /**
   * canvas to file
   * @param {*} canvas 
   */
  canvasToFile(canvas) {
    const b64 = this.canvasToBase64(canvas);
    return this.dataURLtoFile(b64);
  },
  /**
   * 图片转base64
   */
  imageToBase64(img) {
    let canvas = this.imageToCanvas(img);
    return canvas.toDataURL('image/png');
  },
  /**
   * file to image
   * @param file
   * @returns {Promise<any>}
   */
  fileToImage(file) {
    return new Promise((resolve, reject) => {
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function (ev) {
        let result = ev.target.result, img = new Image();
        img.onload = function () {
          resolve(img);
        };
        img.onerror = function () {
          reject({
            code: CODE.NOT_IMAGE,
            msg: '文件不是图片'
          });
        };
        img.src = result;
      }
    })
  },
  /**
   * canvas to image
   * @param canvas
   * @param type
   * @param encoderOptions
   * @returns {Promise<any>}
   */
  canvasToImage(canvas, type = 'image/png', encoderOptions = {}) {
    let url = canvas.toDataURL(type, encoderOptions),
      img = document.createElement('img');
    return new Promise((resolve, reject) => {
      img.onload = function () {
        resolve(img);
      };
      img.onerror = function () {
        reject({
          code: CODE.CANVAS_TO_IMAGE_ERROR
        })
      };
      img.src = url;
    })
  },
  /**
   * file to canvas
   * @param file
   * @param options
   */
  fileToCanvas(file, options = {}) {
    let me = this,
      canvas = document.createElement('canvas'),
      ctx = canvas.getContext('2d');

    return new Promise((resolve, reject) => {
      me.fileToImage(file).then(img => {
        let w = img.naturalWidth, h = img.naturalHeight;
        if (options['width']) {
          // canvas的宽度
          let canvasW = options['width'], canvasH = options['height'];
          canvas.width = canvasW;
          if (canvasH) {
            canvas.height = canvasH;
          } else {
            // 自适应高度
            canvas.height = canvasW * h / w;
          }
        } else {
          canvas.width = w;
          canvas.height = h;
        }
        if (options['cover']) {
          me.drawImageWithCover(ctx, img, 0, 0, canvas.width, canvas.height);
          // 转图片, 传出去的是cover后的图片
          this.canvasToImage(canvas).then(ret => {
            resolve({
              canvas: canvas,
              image: ret
            });
          });
        } else {
          // default contain and center image
          me.drawImageWithContain(canvas, img);
          this.canvasToImage(canvas).then(ret => {
            resolve({
              canvas: canvas,
              image: ret
            });
          })
        }
      })
    });
  },
  /**
   * cover image to canvas
   * @param ctx
   * @param img
   * @param x
   * @param y
   * @param w
   * @param h
   * @param offsetX   x和y的偏移量
   * @param offsetY
   */
  drawImageWithCover(ctx, img, x, y, w, h, offsetX, offsetY) {
    if (arguments.length === 2) {
      x = y = 0;
      w = ctx.canvas.width;
      h = ctx.canvas.height;
    }

    // default offset is center
    offsetX = typeof offsetX === "number" ? offsetX : 0.5;
    offsetY = typeof offsetY === "number" ? offsetY : 0.5;

    // keep bounds [0.0, 1.0]
    if (offsetX < 0) offsetX = 0;
    if (offsetY < 0) offsetY = 0;
    if (offsetX > 1) offsetX = 1;
    if (offsetY > 1) offsetY = 1;

    var iw = img.width,
      ih = img.height,
      r = Math.min(w / iw, h / ih),
      nw = iw * r,   // new prop. width
      nh = ih * r,   // new prop. height
      cx, cy, cw, ch, ar = 1;

    // decide which gap to fill
    if (nw < w) ar = w / nw;
    if (Math.abs(ar - 1) < 1e-14 && nh < h) ar = h / nh;  // updated
    nw *= ar;
    nh *= ar;

    // calc source rectangle
    cw = iw / (nw / w);
    ch = ih / (nh / h);

    cx = (iw - cw) * offsetX;
    cy = (ih - ch) * offsetY;

    // make sure source rectangle is valid
    if (cx < 0) cx = 0;
    if (cy < 0) cy = 0;
    if (cw > iw) cw = iw;
    if (ch > ih) ch = ih;

    ctx.drawImage(img, cx, cy, cw, ch, x, y, w, h);
  },
  /**
   * contain image to canvas
   * @param ctx
   * @param img
   * @param imageW
   * @param imageH
   * @param canvasW
   * @param canvasH
   */
  drawImageWithContain(canvas, img) {
    const ctx = canvas.getContext('2d');
    const scale = Math.min(canvas.width / img.width, canvas.height / img.height);
    const w = img.width * scale, h = img.height * scale;
    const left = canvas.width / 2 - w / 2, top = canvas.height / 2 - h / 2;
    ctx.drawImage(img, left, top, w, h);
  },
  /**
   * rotate image degree
   * @param canvas
   * @param img
   * @param degree
   */
  rotate(canvas, img, degree) {
    let ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.save();
    ctx.translate(canvas.width / 2, canvas.height / 2);
    ctx.rotate(Math.PI / 180 * degree);
    ctx.drawImage(img, -img.width / 2, -img.height / 2);
    ctx.restore();
    return canvas;
  }
}

export default ImageFileConvert;
