  import React, { useState, useEffect } from 'react';
  import { View, Text, StyleSheet, FlatList, Image, Button } from 'react-native';
  import AsyncStorage from '@react-native-async-storage/async-storage';
  import axios from 'axios';
  import { SERVER_IP } from '@env';
  import { TouchableOpacity } from 'react-native';

  const AllPlantsScreen = () => {
    const [userPlants, setUserPlants] = useState([]);
    const [userId, setUserId] = useState(null);

    useEffect(() => {
      getUserPlants();
      getUserId();
    }, []);

    const getUserId = async () => {
      try {
        const token = await AsyncStorage.getItem('loginToken');
        const response = await axios.get(`http://${SERVER_IP}:8000/api/auth/me`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        setUserId(response.data.id);
      } catch (error) {
        console.error('Error fetching user id:', error);
      }
    };

    const getUserPlants = async () => {
      try {
        const token = await AsyncStorage.getItem('loginToken');
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const response = await axios.get(`http://${SERVER_IP}:8000/api/plants/get-all-plants`);
        const decodedPlants = response.data.map(plant => ({
          ...plant,
          image: `data:image/jpeg;base64,${plant.image}`,
        }));
        setUserPlants(decodedPlants);
      } catch (error) {
        console.error('Error fetching plants:', error);
      }
    };

    const handleRemovePlant = async (plantId) => {
      try {
        await axios.patch(`http://${SERVER_IP}:8000/api/plants/remove-plant/${plantId}`);
        // Mettez à jour la liste des plantes après la suppression
        setUserPlants(prevPlants => prevPlants.filter(plant => plant.id !== plantId));
      } catch (error) {
        console.error('Error removing plant:', error);
      }
    };

    const renderPlant = ({ item }) => (
      <View style={styles.plantContainer}>
        <View style={styles.plantDetails}>
          <Image source={{ uri: item.image }} style={styles.plantImage} />
          <Text style={styles.plantName}>{item.nom}</Text>
          <Text style={styles.detailText}>Description: {item.description}</Text>
          <Text style={styles.detailText}>Téléphone: {item.utilisateur.telephone}</Text>
         <Text style={styles.detailText}>Mail: {item.utilisateur.adresse_mail}</Text>
          {userId === item.utilisateur.id && (
            <TouchableOpacity
            style={styles.button} // Ajoutez le style ici
            onPress={() => handleRemovePlant(item.id)} // Utilisez onPress pour gérer l'événement
          >
            <Text style={styles.buttonText}>Retirer</Text>
          </TouchableOpacity>
          )}
        </View>
      </View>
    );

    return (
      <View style={styles.container}>
        <Text style={styles.title}>Bienvenue sur la page recherche</Text>
        <View style={styles.separator}></View>
        <FlatList
          data={userPlants}
          renderItem={renderPlant}
          keyExtractor={(item, index) => index.toString()}
          contentContainerStyle={styles.listContent}
          numColumns={2}
        />
      </View>
    );
  };

  const styles = StyleSheet.create({
    container: {
      flex: 1,
      alignItems: 'center',
      marginVertical: 10,
    },
    title: {
      fontWeight: 'bold',
      fontSize: 18,
      marginBottom: 10,
    },
    separator: {
      width: '90%',
      height: 1,
      backgroundColor: 'grey',
      marginBottom: 10,
    },
    listContent: {
      paddingHorizontal: 10,
    },
    delbutton : {
      borderRadius: 15,
      backgroundColor: 'white',
      width: 150,
    },
    plantContainer: {
      flexDirection: 'row',
      alignItems: 'center',
      backgroundColor: '#f0f0f0',
      borderRadius: 10,
      marginBottom: 10,
      width: '48%',
      marginRight: '2%',
      backgroundColor: '#CFFFD4',
      borderColor: 'green',
      borderWidth: 1,
    },
    plantImage: {
      width: 100,
      height: 100,
      borderRadius: 40,
      marginRight: 10,
    },
    plantDetails: {
      flex: 1,
      justifyContent: 'center',
      marginHorizontal: 10,
      marginVertical: 10,
      alignItems: 'center',
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
    button: {
      backgroundColor: 'white',
      borderRadius: 15,
      paddingHorizontal: 20,
      paddingVertical: 10,
      alignItems: 'center',
      borderColor: 'green',
      borderWidth: 1,
      width: '80%',
      
    },
    buttonText: {
      color: 'green',
      fontWeight: 'bold',
    },
  });

  export default AllPlantsScreen;
