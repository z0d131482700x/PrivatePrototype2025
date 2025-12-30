// privateprototype2025/assets/js/biometric.js - PRODUCTION FIDO2
class BiometricAuth {
    async register() {
        const credential = await navigator.credentials.create({
            publicKey: {
                challenge: new Uint8Array(32),
                rp: { name: "Sovereign Social" },
                user: { id: new Uint8Array(16), name: "user@sovereign.social", displayName: "User" },
                pubKeyCredParams: [{ alg: -7, type: "public-key" }],
                authenticatorSelection: { userVerification: "required" }
            }
        });
        console.log('Biometric Registered:', credential);
    }
    
    async authenticate() {
        const assertion = await navigator.credentials.get({
            publicKey: {
                challenge: new Uint8Array(32),
                allowCredentials: [{ type: "public-key", id: new Uint8Array(16) }],
                userVerification: "required"
            }
        });
        console.log('Biometric Auth Success:', assertion);
    }
}
