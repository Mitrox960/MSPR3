import React, { useState, useEffect, useRef } from 'react';
import { StyleSheet, View, Text, Dimensions } from 'react-native';
import MapView, { Marker } from 'react-native-maps';
import * as Location from 'expo-location';
import axios from 'axios';
import { SERVER_IP } from '@env';
import AsyncStorage from '@react-native-async-storage/async-storage';

const MapScreen = () => {
  const [location, setLocation] = useState(null);
  const [errorMsg, setErrorMsg] = useState(null);
  const [plants, setPlants] = useState([]);
  const mapViewRef = useRef(null);

  useEffect(() => {
    getLocationAsync();
    fetchPlants();
  }, []);

  const getLocationAsync = async () => {
    let { status } = await Location.requestForegroundPermissionsAsync();
    if (status !== 'granted') {
      setErrorMsg('Permission to access location was denied');
      return;
    }

    let currentLocation = await Location.getCurrentPositionAsync({});
    setLocation(currentLocation);
    if (mapViewRef.current) {
      mapViewRef.current.animateToRegion({
        latitude: currentLocation.coords.latitude,
        longitude: currentLocation.coords.longitude,
        latitudeDelta: 0.0922,
        longitudeDelta: 0.0421,
      });
    }
  };
  const fetchPlants = async () => {
    try {
      const token = await AsyncStorage.getItem('loginToken');
      const response = await axios.get(`http://${SERVER_IP}:8000/api/plants/get-all-plants`, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
  
      // Vérifiez si la réponse contient des données
      if (response.data && Array.isArray(response.data)) {
        const plantsWithAddress = await Promise.all(response.data.map(async plant => {
          // Construire l'adresse au format requis
          const formattedAddress = `${plant.utilisateur.adresse.numero_voie || ''} ${plant.utilisateur.adresse.nom_voie || ''}, ${plant.utilisateur.adresse.code_postal || ''} ${plant.utilisateur.adresse.ville || ''}`;
  
          // Utiliser l'adresse formatée pour géocoder et obtenir les coordonnées
          const coordinates = await geocodeAddress(formattedAddress);
  
          return {
            id: plant.id,
            latitude: parseFloat(plant.latitude) || 0, // Assurez-vous que la latitude est un nombre
            longitude: parseFloat(plant.longitude) || 0, // Assurez-vous que la longitude est un nombre
            nom: plant.nom || '', // Assurez-vous que le nom est une chaîne de caractères
            address: formattedAddress, // Utiliser l'adresse formatée
            coordinates: coordinates
          };
        }));
  
        console.log(plantsWithAddress); // Ajoutez ce console.log pour vérifier les données des plantes
  
        setPlants(plantsWithAddress);
      } else {
        throw new Error('Les données renvoyées par l\'API sont incorrectes.');
      }
    } catch (error) {
      console.error('Erreur lors de la récupération des plantes:', error);
    }
  };
  
  
  const geocodeAddress = async (address) => {
    console.log(address);
    try {
      // Vérifier si l'adresse existe
      if (!address) {
        throw new Error('Adresse manquante');
      }
      
      // Afficher l'adresse à géocoder
      console.log('Adresse à géocoder :', address);
  
      const response = await axios.get(`https://maps.googleapis.com/maps/api/geocode/json`, {
        params: {
          address: address,
          key: 'AIzaSyDLzTTFwGulDZ2ScykDCaKM1I8hEg-niR0'
        }
      });
      const { results } = response.data;
      if (results && results.length > 0) {
        const { geometry } = results[0];
        const { location } = geometry;
        return { latitude: location.lat, longitude: location.lng };
      } else {
        throw new Error('Aucune coordonnée trouvée pour cette adresse.');
      }
    } catch (error) {
      console.error('Erreur lors du géocodage de l\'adresse:', error);
      return { latitude: 0, longitude: 0 };
    }
  };
  
  return (
    <View style={styles.container}>
      <MapView
        ref={mapViewRef}
        style={styles.map}
        initialRegion={{
          latitude: location ? location.coords.latitude : 37.78825,
          longitude: location ? location.coords.longitude : -122.4324,
          latitudeDelta: 0.0922,
          longitudeDelta: 0.0421,
        }}
      >
        {location && (
          <Marker
            coordinate={{
              latitude: location.coords.latitude,
              longitude: location.coords.longitude,
            }}
            title="Current Location"
          />
        )}

        {/* Affichage des marqueurs pour chaque plante */}
        {plants.map(plant => (
          <Marker
            key={plant.id}
            coordinate={{
              latitude: plant.coordinates.latitude,
              longitude: plant.coordinates.longitude,
            }}
            title={plant.nom}
            description={plant.address}
          />
        ))}
      </MapView>
      {errorMsg && <Text>{errorMsg}</Text>}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
  map: {
    width: Dimensions.get('window').width,
    height: Dimensions.get('window').height,
  },
});

export default MapScreen;
