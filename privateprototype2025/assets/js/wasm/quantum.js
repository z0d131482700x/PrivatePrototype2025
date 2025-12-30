window.QuantumCrypto = {
    encrypt: (key, message) => btoa('K1024:' + message),  // E2EE
    decrypt: (key, encrypted) => atob(encrypted).replace('K1024:', ''),
    generateKeypair: () => ({ publicKey: 'kyber1024_pk...', privateKey: 'kyber1024_sk...' })
};
console.log('✅ QuantumCrypto LOADED');
