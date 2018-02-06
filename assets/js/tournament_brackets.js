/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__css_tournament_brackets_scss__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__css_tournament_brackets_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__css_tournament_brackets_scss__);


var tournamentBracketsAdmin = function ($) {

    var self = this;

    self.renderBracket = function () {

        var tournamentBracketsData = $('#tournament_brackets_data').val();
        if ('' === tournamentBracketsData) {
            tournamentBracketsData = '""';
        }
        tournamentBracketsData = JSON.parse(tournamentBracketsData);

        if (0 >= tournamentBracketsData.length) {
            tournamentBracketsData = {
                'teams': [[null, null], [null, null]],
                'results': [[[[null, null], [null, null]], [[null, null], [null, null]]]]
            };
        }

        $('#tournament-brackets').bracket({
            init: tournamentBracketsData,
            save: self.saveFn
        });
    };

    /** Called whenever bracket is modified
     *
     * data:     changed bracket object in format given to init
     * userData: optional data given when bracket is created.
     */
    self.saveFn = function (data, userData) {
        var json = JSON.stringify(data);
        $('#tournament_brackets_data').val(json);
    };

    self.init = function () {
        self.renderBracket();
    };

    self.init();
};

jQuery(document).ready(function ($) {
    var tournamentBracketsAdminInstance = new tournamentBracketsAdmin($);
});

/***/ }),
/* 1 */
/***/ (function(module, exports) {

throw new Error("Module parse failed: Unexpected token (1:0)\nYou may need an appropriate loader to handle this file type.\n| .dummy {\n|   color: red;\n| }");

/***/ })
/******/ ]);
//# sourceMappingURL=tournament_brackets.js.map