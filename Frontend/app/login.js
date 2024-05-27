import React, { useState } from 'react';
import { Text, View, StyleSheet, TextInput, Button, Alert, Image } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';
import { useRouter } from 'expo-router'; // Importez le hook useRouter
import { SERVER_IP } from '@env';

export default function Login({ navigation }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const router = useRouter(); // Initialisez le hook useRouter
  const [errorMessage, setErrorMessage] = useState('');

  const handleLogin = async () => {
    try {
  const response = await axios.post(`http://${SERVER_IP}:8000/api/auth/login`, {
        adresse_mail: email,
        password: password,
      });

      if (response.status === 200) {
        await AsyncStorage.setItem('loginToken', response.data.access_token);
        Alert.alert('Success', 'Login successful');
        console.log(response.data.access_token);
        
        // Utilisez le hook useRouter pour rediriger l'utilisateur vers la page "home"
        router.push('/home');
      } else {
        Alert.alert('Error', 'Login failed');
      }

      setEmail('');
      setPassword('');
    } catch (error) {
      setErrorMessage("Le mot de passe / email n'est pas valide");
      return false;
    }
  };

  return (
    <View style={styles.view}>
    <View style={styles.container}>
    <Image source={require('../assets/images/Arosaje.png')}  style={styles.image}/>
      <Text style={styles.title}>LOG IN</Text>
      <Text style={styles.label}>Email</Text>
      <TextInput
        style={styles.input}
        placeholder="Adresse e-mail"
        value={email}
        onChangeText={setEmail}
      />
      <Text style={styles.label}>Password</Text>
      <TextInput
        style={styles.input}
        placeholder="Mot de passe"
        secureTextEntry={true}
        value={password}
        onChangeText={setPassword}
      />
            {errorMessage ? <TextInput style={styles.errorMessage}>{errorMessage}</TextInput> : null}

      <Button title="Se connecter" onPress={handleLogin} style={styles.button}
/>

      <Text style={styles.forgotPasswordText} onPress={() => navigation.navigate("RequestOTP")}>
        J'ai perdu mon mot de passe
      </Text>

      <Button
        title="Register Here"
        onPress={() => navigation.navigate("Register")}
        style={styles.button}
      />
    </View>
    </View>
  );
}

const styles = StyleSheet.create({
  view: {
    flex: 1,
    backgroundColor: '#CFFFD4', // Bleu clair pour un aspect futuriste
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
  },
  container: {
    backgroundColor : 'white',
    padding: 15,
    borderRadius: 15,

  },
  title: {
    textAlign: 'center',
    fontWeight: 'bold',
    marginBottom: 15,


  },
  form: {
    width: '100%',
  },
  input: {
    backgroundColor: '#fff',
    borderColor: '#ccc',
    borderWidth: 1,
    borderRadius: 5,
    padding: 10,
    marginBottom: 10,
  },
  errorMessage: {
    color: 'red',
    marginBottom: 10,
  },
  forgotPasswordText: {
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 10,

  },
  button: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
    backgroundColor: '#2BDB3E',
    shadowColor: 'white',
    width: 100,
  },
  image: {
    width: 280,
    height:200,
  },
  login:{
    width: 28,
    height:20,
  }

});

