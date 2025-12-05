import * as THREE from 'three';
import {EffectComposer} from 'three/addons/postprocessing/EffectComposer.js';
import {RenderPass} from 'three/addons/postprocessing/RenderPass.js';
import {UnrealBloomPass} from 'three/addons/postprocessing/UnrealBloomPass.js';
import {OutputPass} from 'three/addons/postprocessing/OutputPass.js';
import {ShaderPass} from 'three/addons/postprocessing/ShaderPass.js';

const renderer = new THREE.WebGLRenderer({antialias: true, alpha: true});
const container = document.getElementById('canvas-container');
renderer.setSize(container.clientWidth, container.clientHeight);
container.appendChild(renderer.domElement);
renderer.setClearColor(0x000000, 0);

/**
 * Colors : #000055, #D2C72F, #D9F103
 * Font : Bebas Neue
 */

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(
	45,
	container.clientWidth / container.clientHeight,
	0.1,
	1000
);

const params = {
	red: 0.0,
	green: 0.0,
	blue: 0.33,
	threshold: 0.0,
	strength: 0.3,
	radius: 0.1
}

renderer.outputColorSpace = THREE.SRGBColorSpace;

const renderScene = new RenderPass(scene, camera);

const bloomPass = new UnrealBloomPass(new THREE.Vector2(container.clientWidth, container.clientHeight));
bloomPass.threshold = params.threshold;
bloomPass.strength = params.strength;
bloomPass.radius = params.radius;

const bloomComposer = new EffectComposer(renderer);
bloomComposer.addPass(renderScene);
bloomComposer.addPass(bloomPass);

const outputPass = new OutputPass();
bloomComposer.addPass(outputPass);

camera.position.set(-8, -2, 14);
camera.lookAt(-4, 0, 0);

const uniforms = {
	u_time: {type: 'f', value: 0.0},
	u_frequency: {type: 'f', value: 0.0},
	u_red: {type: 'f', value: 0.0},
	u_green: {type: 'f', value: 0.0},
	u_blue: {type: 'f', value: 0.33}
}

const mat = new THREE.ShaderMaterial({
	uniforms,
	vertexShader: document.getElementById('vertexshader').textContent,
	fragmentShader: document.getElementById('fragmentshader').textContent
});

const geo = new THREE.IcosahedronGeometry(3, 32);
const mesh = new THREE.Mesh(geo, mat);
mesh.position.set(-9, 0, 0);
scene.add(mesh);
mesh.material.wireframe = true;

// Create inner sphere with lighter color
const innerUniforms = {
	u_time: {type: 'f', value: 0.0},
	u_frequency: {type: 'f', value: 0.0},
	u_red: {type: 'f', value: 0.40},
	u_green: {type: 'f', value: 0.12},
	u_blue: {type: 'f', value: 0.70}
}

const innerMat = new THREE.ShaderMaterial({
	uniforms: innerUniforms,
	vertexShader: document.getElementById('vertexshader').textContent,
	fragmentShader: document.getElementById('fragmentshader').textContent
});

const innerGeo = new THREE.IcosahedronGeometry(1.8, 32);
const innerMesh = new THREE.Mesh(innerGeo, innerMat);
innerMesh.position.set(-9, 0, 0);
scene.add(innerMesh);
innerMesh.material.wireframe = true;

// Create background particles for atmosphere
const particleCount = 150;
const particlesGeometry = new THREE.BufferGeometry();
const particlesPositions = new Float32Array(particleCount * 3);
const particlesSpeeds = [];

for (let i = 0; i < particleCount; i++) {
	particlesPositions[i * 3] = (Math.random() - 0.5) * 50;
	particlesPositions[i * 3 + 1] = (Math.random() - 0.5) * 30;
	particlesPositions[i * 3 + 2] = (Math.random() - 0.5) * 40 - 10;
	
	particlesSpeeds.push({
		x: (Math.random() - 0.5) * 0.02,
		y: (Math.random() - 0.5) * 0.02,
		z: (Math.random() - 0.5) * 0.01
	});
}

particlesGeometry.setAttribute('position', new THREE.BufferAttribute(particlesPositions, 3));

const particlesMaterial = new THREE.PointsMaterial({
	color: 0x6366f1,
	size: 0.15,
	transparent: true,
	opacity: 0.6,
	blending: THREE.AdditiveBlending
});

const particles = new THREE.Points(particlesGeometry, particlesMaterial);
scene.add(particles);

// Create line system for connecting particles
const maxConnections = particleCount * 2;
const linePositions = new Float32Array(maxConnections * 6);
const lineOpacities = new Float32Array(maxConnections * 2).fill(0);
const lineGeometry = new THREE.BufferGeometry();
lineGeometry.setAttribute('position', new THREE.BufferAttribute(linePositions, 3));

const lineMaterial = new THREE.LineBasicMaterial({
	color: 0x6366f1,
	transparent: true,
	opacity: 0.3,
	blending: THREE.AdditiveBlending
});

const lineSystem = new THREE.LineSegments(lineGeometry, lineMaterial);
scene.add(lineSystem);

const maxConnectionDistance = 5;
const lineConnections = new Map(); // Track active connections with animation progress
const lineAnimationSpeed = 0.05; // Speed of line growth animation

const listener = new THREE.AudioListener();
camera.add(listener);

// Create audio context and connect to video element
const videoElement = document.getElementById('video');
const audioContext = new (window.AudioContext || window.webkitAudioContext)();
const analyser = audioContext.createAnalyser();
analyser.fftSize = 64;

// Connect video audio to analyser
const source = audioContext.createMediaElementSource(videoElement);
source.connect(analyser);
source.connect(audioContext.destination);

// Video container for effects
const videoContainer = document.getElementById('video-container');
const pauseIndicator = document.getElementById('pause-indicator');

// Play/Pause video on click
videoElement.addEventListener('click', async (e) => {
	e.stopPropagation();
	
	// First interaction: start audio and video
	if (audioContext.state === 'suspended') {
		videoElement.muted = false;
		videoElement.volume = 0.5;
		await audioContext.resume();
	}
	
	if (videoElement.paused) {
		await videoElement.play();
		pauseIndicator.classList.add('hidden');
	} else {
		videoElement.pause();
		pauseIndicator.classList.remove('hidden');
	}
});

// Prevent video container click from affecting video
videoContainer.addEventListener('click', (e) => {
	if (e.target === videoContainer) {
		e.stopPropagation();
	}
});

// Helper function to get frequency
function getAverageFrequency() {
	const dataArray = new Uint8Array(analyser.frequencyBinCount);
	analyser.getByteFrequencyData(dataArray);
	return dataArray.reduce((sum, value) => sum + value, 0) / dataArray.length;
}

const clock = new THREE.Clock();

// Video zone boundaries (screen coordinates)
const getVideoZone = () => {
	const rect = videoContainer.getBoundingClientRect();
	return {
		left: rect.left,
		right: rect.right,
		top: rect.top,
		bottom: rect.bottom
	};
};

function animate() {
	const frequency = getAverageFrequency();
	
	uniforms.u_time.value = clock.getElapsedTime();
	uniforms.u_frequency.value = frequency;
	
	innerUniforms.u_time.value = clock.getElapsedTime();
	innerUniforms.u_frequency.value = frequency;
	
	// 1. Border glow pulsant avec la musique
	const normalizedFrequency = frequency / 256.0;
	const glowIntensity = 50 + (normalizedFrequency * 100);
	const glowColor = `rgba(99, 102, 241, ${0.5 + normalizedFrequency * 0.8})`;
	videoElement.style.boxShadow = `0 0 ${glowIntensity}px ${glowColor}, 0 0 ${glowIntensity * 1.5}px ${glowColor}`;
	
	// 3. Effet de flottement 3D
	const floatY = Math.sin(clock.getElapsedTime() * 0.5) * 25 * (0.5 + normalizedFrequency);
	const rotateX = Math.sin(clock.getElapsedTime() * 0.3) * 8 * normalizedFrequency;
	const rotateY = Math.cos(clock.getElapsedTime() * 0.4) * 5 * normalizedFrequency;
	videoContainer.style.transform = `translateY(calc(-50% + ${floatY}px))`;
	videoElement.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
	
	// Get video zone for particle avoidance
	const videoZone = getVideoZone();
	
	// Animate background particles
	const positions = particlesGeometry.attributes.position.array;
	for (let i = 0; i < particleCount; i++) {
		// 4. Particules évitent la zone vidéo
		const screenPos = new THREE.Vector3(
			positions[i * 3],
			positions[i * 3 + 1],
			positions[i * 3 + 2]
		).project(camera);
		
		const x = (screenPos.x * 0.5 + 0.5) * container.clientWidth;
		const y = ((-screenPos.y) * 0.5 + 0.5) * container.clientHeight;
		
		// Check if particle is in video zone
		if (x > videoZone.left && x < videoZone.right && y > videoZone.top && y < videoZone.bottom) {
			// Push particle away from video center
			const videoCenterX = (videoZone.left + videoZone.right) / 2;
			const videoCenterY = (videoZone.top + videoZone.bottom) / 2;
			const dirX = x - videoCenterX;
			const dirY = y - videoCenterY;
			const dist = Math.sqrt(dirX * dirX + dirY * dirY);
			
			if (dist > 0) {
				particlesSpeeds[i].x += (dirX / dist) * 0.001;
				particlesSpeeds[i].y -= (dirY / dist) * 0.001;
			}
		}
		
		positions[i * 3] += particlesSpeeds[i].x;
		positions[i * 3 + 1] += particlesSpeeds[i].y;
		positions[i * 3 + 2] += particlesSpeeds[i].z;
		
		// Reset particles that go too far
		if (Math.abs(positions[i * 3]) > 25) particlesSpeeds[i].x *= -1;
		if (Math.abs(positions[i * 3 + 1]) > 15) particlesSpeeds[i].y *= -1;
		if (positions[i * 3 + 2] > 20 || positions[i * 3 + 2] < -30) particlesSpeeds[i].z *= -1;
	}
	particlesGeometry.attributes.position.needsUpdate = true;
	
	// Update particle connections with animated growth
	const linePositions = lineGeometry.attributes.position.array;
	let lineIndex = 0;
	const currentConnections = new Set();
	
	for (let i = 0; i < particleCount; i++) {
		const ix = positions[i * 3];
		const iy = positions[i * 3 + 1];
		const iz = positions[i * 3 + 2];
		
		// Find closest particle
		let closestDist = Infinity;
		let closestIndex = -1;
		
		for (let j = i + 1; j < particleCount; j++) {
			const jx = positions[j * 3];
			const jy = positions[j * 3 + 1];
			const jz = positions[j * 3 + 2];
			
			const dx = ix - jx;
			const dy = iy - jy;
			const dz = iz - jz;
			const dist = Math.sqrt(dx * dx + dy * dy + dz * dz);
			
			if (dist < closestDist && dist < maxConnectionDistance) {
				closestDist = dist;
				closestIndex = j;
			}
		}
		
		// Create animated line to closest particle
		if (closestIndex !== -1 && lineIndex < maxConnections * 2) {
			const connectionKey = `${i}-${closestIndex}`;
			currentConnections.add(connectionKey);
			
			// Get or create animation progress for this connection
			let progress = 0;
			if (lineConnections.has(connectionKey)) {
				progress = Math.min(1, lineConnections.get(connectionKey) + lineAnimationSpeed);
			} else {
				progress = lineAnimationSpeed;
			}
			lineConnections.set(connectionKey, progress);
			
			// Calculate midpoint
			const jx = positions[closestIndex * 3];
			const jy = positions[closestIndex * 3 + 1];
			const jz = positions[closestIndex * 3 + 2];
			const midX = (ix + jx) / 2;
			const midY = (iy + jy) / 2;
			const midZ = (iz + jz) / 2;
			
			// Animate from center outward (progress 0 = at center, progress 1 = fully extended)
			const startX = midX + (ix - midX) * progress;
			const startY = midY + (iy - midY) * progress;
			const startZ = midZ + (iz - midZ) * progress;
			const endX = midX + (jx - midX) * progress;
			const endY = midY + (jy - midY) * progress;
			const endZ = midZ + (jz - midZ) * progress;
			
			// Smooth opacity based on distance and progress
			const distanceOpacity = 1 - (closestDist / maxConnectionDistance);
			const opacity = distanceOpacity * progress;
			lineOpacities[lineIndex] = opacity;
			lineOpacities[lineIndex + 1] = opacity;
			
			linePositions[lineIndex * 3] = startX;
			linePositions[lineIndex * 3 + 1] = startY;
			linePositions[lineIndex * 3 + 2] = startZ;
			lineIndex++;
			
			linePositions[lineIndex * 3] = endX;
			linePositions[lineIndex * 3 + 1] = endY;
			linePositions[lineIndex * 3 + 2] = endZ;
			lineIndex++;
		}
	}
	
	// Animate disconnecting lines (shrink back to center)
	const disconnectingLines = [];
	lineConnections.forEach((progress, key) => {
		if (!currentConnections.has(key)) {
			const newProgress = Math.max(0, progress - lineAnimationSpeed);
			if (newProgress > 0) {
				disconnectingLines.push({ key, progress: newProgress });
				lineConnections.set(key, newProgress);
				
				// Parse connection key to get particle indices
				const [i, j] = key.split('-').map(Number);
				const ix = positions[i * 3];
				const iy = positions[i * 3 + 1];
				const iz = positions[i * 3 + 2];
				const jx = positions[j * 3];
				const jy = positions[j * 3 + 1];
				const jz = positions[j * 3 + 2];
				
				const midX = (ix + jx) / 2;
				const midY = (iy + jy) / 2;
				const midZ = (iz + jz) / 2;
				
				// Shrink toward center
				const startX = midX + (ix - midX) * newProgress;
				const startY = midY + (iy - midY) * newProgress;
				const startZ = midZ + (iz - midZ) * newProgress;
				const endX = midX + (jx - midX) * newProgress;
				const endY = midY + (jy - midY) * newProgress;
				const endZ = midZ + (jz - midZ) * newProgress;
				
				const opacity = 0.3 * newProgress;
				lineOpacities[lineIndex] = opacity;
				lineOpacities[lineIndex + 1] = opacity;
				
				linePositions[lineIndex * 3] = startX;
				linePositions[lineIndex * 3 + 1] = startY;
				linePositions[lineIndex * 3 + 2] = startZ;
				lineIndex++;
				
				linePositions[lineIndex * 3] = endX;
				linePositions[lineIndex * 3 + 1] = endY;
				linePositions[lineIndex * 3 + 2] = endZ;
				lineIndex++;
			} else {
				lineConnections.delete(key);
			}
		}
	});
	
	// Clear unused lines
	for (let i = lineIndex; i < maxConnections * 2; i++) {
		linePositions[i * 3] = 0;
		linePositions[i * 3 + 1] = 0;
		linePositions[i * 3 + 2] = 0;
	}
	
	lineGeometry.setDrawRange(0, lineIndex);
	lineGeometry.attributes.position.needsUpdate = true;
	
	// Make bloom strength pulse with music
	bloomPass.strength = params.strength + (normalizedFrequency * 0.5);
	
    bloomComposer.render();
	requestAnimationFrame(animate);
}
animate();

window.addEventListener('resize', function() {
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
	bloomComposer.setSize(container.clientWidth, container.clientHeight);
});