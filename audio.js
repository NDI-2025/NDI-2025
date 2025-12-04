import * as THREE from 'three';
import {EffectComposer} from 'three/addons/postprocessing/EffectComposer.js';
import {RenderPass} from 'three/addons/postprocessing/RenderPass.js';
import {UnrealBloomPass} from 'three/addons/postprocessing/UnrealBloomPass.js';
import {OutputPass} from 'three/addons/postprocessing/OutputPass.js';

const renderer = new THREE.WebGLRenderer({antialias: true});
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);
//renderer.setClearColor(0x222222);

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(
	45,
	window.innerWidth / window.innerHeight,
	0.1,
	1000
);

const params = {
	red: 1.0,
	green: 1.0,
	blue: 1.0,
	threshold: 0.5,
	strength: 0.5,
	radius: 0.8
}

renderer.outputColorSpace = THREE.SRGBColorSpace;

const renderScene = new RenderPass(scene, camera);

const bloomPass = new UnrealBloomPass(new THREE.Vector2(window.innerWidth, window.innerHeight));
bloomPass.threshold = params.threshold;
bloomPass.strength = params.strength;
bloomPass.radius = params.radius;

const bloomComposer = new EffectComposer(renderer);
bloomComposer.addPass(renderScene);
bloomComposer.addPass(bloomPass);

const outputPass = new OutputPass();
bloomComposer.addPass(outputPass);

camera.position.set(0, -2, 14);
camera.lookAt(0, 0, 0);

const uniforms = {
	u_time: {type: 'f', value: 0.0},
	u_frequency: {type: 'f', value: 0.0},
	u_red: {type: 'f', value: 1.0},
	u_green: {type: 'f', value: 1.0},
	u_blue: {type: 'f', value: 1.0}
}

const mat = new THREE.ShaderMaterial({
	uniforms,
	vertexShader: document.getElementById('vertexshader').textContent,
	fragmentShader: document.getElementById('fragmentshader').textContent
});

const geo = new THREE.IcosahedronGeometry(4, 30 );
const mesh = new THREE.Mesh(geo, mat);
scene.add(mesh);
mesh.material.wireframe = true;

const listener = new THREE.AudioListener();
camera.add(listener);

const sound = new THREE.Audio(listener);

const audioLoader = new THREE.AudioLoader();
audioLoader.load('./assets/song.mp3', function(buffer) {
	sound.setBuffer(buffer);
	window.addEventListener('click', function() {
		sound.play();
	});
});

const analyser = new THREE.AudioAnalyser(sound, 32);

let mouseX = 0;
let mouseY = 0;
document.addEventListener('mousemove', function(e) {
	let windowHalfX = window.innerWidth / 2;
	let windowHalfY = window.innerHeight / 2;
	mouseX = (e.clientX - windowHalfX) / 100;
	mouseY = (e.clientY - windowHalfY) / 100;
});

const clock = new THREE.Clock();
function animate() {
	camera.position.x += (mouseX - camera.position.x) * .05;
	camera.position.y += (-mouseY - camera.position.y) * 0.5;
	camera.lookAt(scene.position);
	uniforms.u_time.value = clock.getElapsedTime();
	uniforms.u_frequency.value = analyser.getAverageFrequency();
    bloomComposer.render();
	requestAnimationFrame(animate);
}
animate();

window.addEventListener('resize', function() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
	bloomComposer.setSize(window.innerWidth, window.innerHeight);
});