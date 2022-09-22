// Jquery
window.$ = window.jQuery = require("jquery");

// Bootstrap
require("bootstrap");

// Validate CPF
import { validate as validateCPF } from 'gerador-validador-cpf'
window.validateCPF = validateCPF;

/** Credit card helper */
window.creditCardType = require('credit-card-type');
window.creditCardsBrand = require('credit-card-type').types;

// Vanilla Masker
window.VMasker = require("vanilla-masker");

// Jquery modal
require("./vendor/jquery.modal");

// lazysizes
require("./vendor/lazysizes.min");

// Owl Carousel
require("./vendor/owl.carousel.min");

// Lightbox
require("./vendor/lightbox.min");

// Icones
require("./iconsBundle");

// Datepickk
window.Datepickk = require("./vendor/datepickk");

// App Helpers
window.Helpers = require("./helpers");

// Google
window.Google = require("./factory/Google");

// Facebook
window.Facebook = require("./factory/Facebook");

// Biblioteca dos dados
window.DadosFactory = require("./factory/DadosFactory");

// Biblioteca Axios
window.axios = require('axios');
