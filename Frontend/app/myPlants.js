import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, FlatList, Image, TouchableOpacity } from 'react-native';
import axios from 'axios';
import { SERVER_IP } from '@env';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function MyPlantsScreen() {
  const [userPlants, setUserPlants] = useState([]);

  useEffect(() => {
    getUserPlants();
  }, []);

  const getUserPlants = async () => {
    try {
      const token = await AsyncStorage.getItem('loginToken');
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      const response = await axios.get(`http://${SERVER_IP}:8000/api/plants/get-user-plants`);
      const decodedPlants = response.data.map(plant => ({
        ...plant,
        image: `data:image/jpeg;base64,${plant.image}`,
        isPosted: false // Initialisez la propriété à false par défaut
      }));
      setUserPlants(decodedPlants);
    } catch (error) {
      console.error('Erreur lors de la récupération des plantes:', error);
    }
  };

  const handlePostPlant = async (plantId, index) => {
    try {
      const token = await AsyncStorage.getItem('loginToken');
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      await axios.patch(`http://${SERVER_IP}:8000/api/plants/post-plant/${plantId}`);
      // Mettre à jour l'état local pour indiquer que la plante a été postée
      setUserPlants(prevState => {
        const updatedPlants = [...prevState];
        updatedPlants[index].isPosted = true;
        return updatedPlants;
      });
    } catch (error) {
      console.error('Erreur lors de la publication de la plante:', error);
    }
  };

  const handleRemovePlant = async (plantId, index) => {
    try {
      const token = await AsyncStorage.getItem('loginToken');
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      await axios.patch(`http://${SERVER_IP}:8000/api/plants/remove-plant/${plantId}`);
      // Mettre à jour l'état local pour indiquer que la plante a été retirée
      setUserPlants(prevState => {
        const updatedPlants = [...prevState];
        updatedPlants[index].isPosted = false;
        return updatedPlants;
      });
    } catch (error) {
      console.error('Erreur lors du retrait de la plante:', error);
    }
  };

  const handleDeletePlant = async (plantId, index) => {
    try {
      const token = await AsyncStorage.getItem('loginToken');
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      await axios.delete(`http://${SERVER_IP}:8000/api/plants/delete-plant/${plantId}`);
      // Mettre à jour l'état local pour supprimer la plante de la liste
      setUserPlants(prevState => {
        const updatedPlants = [...prevState];
        updatedPlants.splice(index, 1); // Supprime la plante à l'index donné
        return updatedPlants;
      });
    } catch (error) {
      console.error('Erreur lors de la suppression de la plante:', error);
    }
  };

  return (
    <View style={styles.container}>
      <FlatList
        data={userPlants}
        renderItem={({ item, index }) => (
          <View style={styles.plantContainer}>
            <Image source={{ uri: item.image }} style={styles.plantImage} />
            <View style={styles.plantDetails}>
              <Text style={styles.plantName}>{item.nom}</Text>
              <Text style={styles.detailText}>Description: {item.description}</Text>
              <Text style={styles.detailText}>Conseil d'entretien: {item.conseil_entretien}</Text>
              {item.isPosted ? (
                <TouchableOpacity onPress={() => handleRemovePlant(item.id, index)}>
                  <View style={styles.removeButton}>
                    <Text style={styles.buttonText}>Retirer</Text>
                  </View>
                </TouchableOpacity>
              ) : (
                <TouchableOpacity onPress={() => handlePostPlant(item.id, index)}>
                  <View style={styles.postButton}>
                    <Text style={styles.buttonText}>Poster</Text>
                  </View>
                </TouchableOpacity>
              )}
                <TouchableOpacity onPress={() => handleDeletePlant(item.id, index)}>
                  <View style={styles.deleteButton}>
                    <Text style={styles.buttonText}>Supprimer</Text>
                  </View>
                </TouchableOpacity>
            </View>
          </View>
        )}
        keyExtractor={(item, index) => index.toString()}
        contentContainerStyle={styles.listContent}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  listContent: {
    marginTop: 20,
    alignItems: 'center',
    paddingHorizontal: 10,
  },
  plantContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f0f0f0',
    borderRadius: 10,
    marginBottom: 10,
    padding: 10,
    width: '100%',
  },
  plantImage: {
    width: 80,
    height: 80,
    borderRadius: 40,
    marginRight: 10,
  },
  plantDetails: {
    flex: 1,
  },
  plantName: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 5,
  },
  detailText: {
    marginTop: 15,
    fontSize: 16,
  },
  postButton: {
    backgroundColor: '#24C143',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 5,
  },
  deleteButton: {
    backgroundColor: 'gray', // Changez la couleur en gris ou toute autre couleur de votre choix
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 5,
  },
  removeButton: {
    backgroundColor: 'red', // Changez la couleur en rouge ou toute autre couleur de votre choix
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 5,
  },
  buttonText: {
    color: 'white',
    fontWeight: 'bold',
  },
});
