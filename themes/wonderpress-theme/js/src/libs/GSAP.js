const {gsap}          = require("gsap/dist/gsap");
const {CSSRulePlugin} = require("gsap/dist/CSSRulePlugin");
const {CustomEase}    = require("gsap/dist/CustomEase");
const {ScrollTrigger} = require("gsap/dist/ScrollTrigger");
window.gsap           = gsap;

gsap.registerPlugin(CSSRulePlugin, CustomEase, ScrollTrigger);

CustomEase.create("timingFuncEaseOutBack2", "M0,0 C0.128,0.572 0.274,1.005 0.538,1.04 0.706,1.062 0.838,1 1,1");
CustomEase.create("timingFuncEaseOutBack3", "M0,0 C0.128,0.572 0.316,0.987 0.58,1.022 0.748,1.044 0.838,1 1,1 ");
