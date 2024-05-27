import React, { useState } from 'react';
import { View, Text, StyleSheet, TextInput, Alert, ScrollView, TouchableOpacity, Image } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import axios from 'axios';
import { SERVER_IP } from '@env';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function ImagePickerExample() {
  const [image, setImage] = useState(null);
  const [nom, setNom] = useState('');
  const [description, setDescription] = useState('');
  const [conseilEntretien, setConseilEntretien] = useState('');
  const [errorMessage, setErrorMessage] = useState('');

  const pickImageFromGallery = async () => {
    let result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.All,
      allowsEditing: true,
      aspect: [4, 3],
      quality: 1,
    });

    if (!result.cancelled) {
      setImage(result.assets[0].uri);
    }
  };

  const takePhoto = async () => {
    let result = await ImagePicker.launchCameraAsync({
      allowsEditing: true,
      aspect: [4, 3],
      quality: 1,
    });

    if (!result.cancelled) {
      setImage(result.uri);
    }
  };

  const handleSubmit = async () => {
    if (!nom || !description || !conseilEntretien || !image) {
      Alert.alert('Tous les champs sont requis');
      return;
    }

    try {
      const token = await AsyncStorage.getItem('loginToken');
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

      const formData = new FormData();
      formData.append('nom', nom);
      formData.append('description', description);
      formData.append('conseil_entretien', conseilEntretien);
      formData.append('image', {
        uri: image,
        name: 'image.jpg',
        type: 'image/jpg',
      });

      const response = await axios.post(`http://${SERVER_IP}:8000/api/plants/createplant`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      if (response.status === 201) {
        Alert.alert('Plante ajoutée avec succès!');
        setNom('');
        setDescription('');
        setConseilEntretien('');
        setImage(null);
      } else {
        console.error('Erreur lors de l\'envoi de la requête:', response.status);
        Alert.alert('Une erreur s\'est produite. Veuillez réessayer.');
      }
    } catch (error) {      
      setErrorMessage("Erreur lors de l'upload, image invalide");

      return false;
    }
  };

  return (
    <ScrollView contentContainerStyle={styles.view}>
      <View style={styles.container}>
        <Text style={styles.title}>Ajouter une plante</Text>
        <Image source={require('../assets/images/Arosaje.png')} style={styles.image} />

        <TextInput
          placeholder="Nom de la plante"
          style={styles.input}
          value={nom}
          onChangeText={text => setNom(text)}
        />
        <TextInput
          placeholder="Description de la plante"
          style={styles.input}
          value={description}
          onChangeText={text => setDescription(text)}
        />
        <TextInput
          placeholder="Conseil d'entretien"
          style={styles.input}
          value={conseilEntretien}
          onChangeText={text => setConseilEntretien(text)}
        />
       <Text style={styles.photo} >Entrer une photo de la plante</Text>

        <View style={styles.addpictureplant}>
        <TouchableOpacity style={styles.button} onPress={pickImageFromGallery}>
          <Image source={require('../assets/images/albumaddplant.png')} style={styles.imagepicture} />
      </TouchableOpacity>
        <TouchableOpacity style={styles.button} onPress={takePhoto}>
        <Image source={require('../assets/images/photoaddplant.png')} style={styles.imagepicture} />
        </TouchableOpacity>
        </View>
        {image && <Image source={{ uri: image }} style={styles.image} />}
        {errorMessage ? <TextInput style={styles.errorMessage}>{errorMessage}</TextInput> : null}

        <TouchableOpacity style={styles.addplantButton} onPress={handleSubmit}>
          <Text style={styles.buttonText}>Ajouter la plante</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: 'white',
    width: 280,
    paddingVertical: 50,
    paddingHorizontal: 10,
    borderRadius: 10,
  },
  title: {
    textAlign: 'center',
    fontWeight: 'bold',
    marginBottom: 15,
    fontSize: 25,
  },
  addpictureplant: {
    flexDirection: 'row'
  },
  image: {
    width: 200,
    height: 150,
    marginBottom: 30,
  },
  input: {
    width: '80%',
    marginBottom: 10,
    padding: 10,
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 5,
  },
  imagepicture:{
    width: 40,
    height: 40,
  },
  view: {
    flexGrow: 1,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#CFFFD4', // Bleu clair pour un aspect futuriste
    padding: 20,
  },
  photo : {
    marginTop: 15,
    marginBottom: 5,
  },
  button: {
    backgroundColor: '#00bf0d',
    borderRadius: 15,
    paddingHorizontal: 20,
    paddingVertical: 10,
    marginBottom: 10,
    alignItems: 'center',
    marginHorizontal: 5,
  },
  addplantButton: {
    backgroundColor: '#00bf0d',
    borderRadius: 15,
    paddingHorizontal: 20,
    paddingVertical: 10,
    marginBottom: 10,
    width: '80%',
    alignItems: 'center',
  },
  buttonText: {
    color: 'white',
    fontWeight: 'bold',
  },
});
