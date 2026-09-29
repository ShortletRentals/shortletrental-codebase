// import { StyleSheet, Text, View } from 'react-native'
// import React,{useState,createRef} from 'react'
// import MapView,{Marker,PROVIDER_GOOGLE} from 'react-native-maps'

// const MapScreen = () => {
//   const [region, setRegion] = useState ({
//     latitude: 22.25973582041688,
//     longitude: 70.79867117106915,
//     latitudeDelta: 0.4,
//     longitudeDelta: 0.4,
// })
// const mapView =()=>{
//   createRef();
// } 
// const  onRegionChange =  (region)=> {
//     setRegion({ region });
//   }

// //   const onRegionChangeComplete = ()=>{

// //   }
//   return (
//     <View style={{flex:1}}>
//       {/* <Text>MapScreen</Text> */}

//       <MapView
//         // provider ={PROVIDER_GOOGLE}
//         provider="google"
//         style={{height:'100%',width:'100%'}}
//         // style = {styles.map}
//         // customMapStyle ={mapStyle}
//         showsUserLocation ={true}
//         followsUserLocation = {true}
//         initialRegion={{
//           latitude: region.latitude,
//           longitude: region.longitude,
//           latitudeDelta: 0.00922,
//           longitudeDelta: 0.00421,
//       }}
//       ref={mapView}
//      onRegionChange={onRegionChange}
//       >
//         <Marker
//                 coordinate={{ latitude: region.latitude,longitude: region.longitude}}
//                 draggable
//                 flat
//                 // onDragEnd = {(e) => {
//                 //     setRegion({
//                 //         latitude: e.nativeEvent.coordinate.latitude,
//                 //         longitude: e.nativeEvent.coordinate.longitude
//                 //     })
                    
//                 //     console.log("Drag End", e.nativeEvent.coordinate)
//                 // }}
//                 anchor={{x:0,y:0}}

//             ></Marker>
             

          
//         </MapView>
//     </View>
//   )
// }

// export default MapScreen

import React, { Component } from 'react';
import { View, Text ,StyleSheet,Dimensions} from 'react-native';
// import styles from '../styles/home-styles';
import MapView, { Marker } from 'react-native-maps';
import Geolocation from 'react-native-geolocation-service'

export default class MyHomeScreen extends Component {

    constructor(props) {
        super(props);

        this.state = {
            initialRegion: {
                latitude: 12.899305,
                longitude: 77.634118,
                latitudeDelta: 0.0922,
                longitudeDelta: 0.0421
            }
        }
    }

    componentDidMount() {
    Geolocation.getCurrentPosition((position) => {
            var lat = parseFloat(position.coords.latitude)
            var long = parseFloat(position.coords.longitude)
            var initialRegion = {
                latitude: lat,
                longitude: long,
                latitudeDelta: 0.0922,
                longitudeDelta: 0.0421
            }
            this.setState({ initialRegion: initialRegion })
        },
            (error) => alert(JSON.stringify(error)),
            { enableHighAccuracy: true, timeout: 20000, maximumAge: 1000 });
    }

    render() {
        return (
            <View style={styles.container}>
                    <View style={styles.mapContainer}>
                        <MapView style={styles.map}
                            initialRegion={this.state.initialRegion}
                            showsUserLocation={true}>
                        {!!this.state.initialRegion.latitude && !!this.state.initialRegion.longitude && <Marker.Animated
                            coordinate={{ "latitude": this.state.initialRegion.latitude, "longitude": this.state.initialRegion.longitude }}
                            title={"Your Location"}
                            
                        />}
                        </MapView>
                </View>
            </View>
        );
    }
}
const styles=StyleSheet.create({
    container:{
        flex: 1,
      },mapContainer: {
        flex: 1,
      },
      map: {
        flex: 1,
        width: Dimensions.get("window").width,
        height: Dimensions.get("window").height,
      }
});

// import React, { Component } from 'react';
// import { View, Text, TouchableOpacity, Image, TextInput, ToastAndroid ,StyleSheet,Dimensions} from 'react-native';
// // import styles from '../styles/home-styles';
// import MapView, { Marker, Callout, Polyline } from 'react-native-maps';
// // import { GooglePlacesAutocomplete } from 'react-native-google-places-autocomplete';
// import Geolocation from 'react-native-geolocation-service'
// const mini = [{
//     title: '3\nmin',
//     coordinates: {
//         latitude: 12.899844,
//         longitude: 77.631634
//     }
// },
// {
//     title: '4\nmin',
//     coordinates: {
//         latitude: 12.902254,
//         longitude: 77.629027
//     }
// }]

// const sedan = [{
//     title: '5\nmin',
//     coordinates: {
//         latitude: 12.905925,
//         longitude: 77.632347
//     }
// },
// {
//     title: '6\nmin',
//     coordinates: {
//         latitude: 12.894442,
//         longitude: 77.635421
//     }
// }]

// const suv = [{
//     title: '2\nmin',
//     coordinates: {
//         latitude: 12.899844,
//         longitude: 77.631634
//     }
// },
// {
//     title: '7\nmin',
//     coordinates: {
//         latitude: 12.905925,
//         longitude: 77.632347
//     }
// }]


// export default class MyHomeScreen extends Component {



//     constructor(props) {
//         super(props);

//         this.state = {
//             initialRegion: {
//                 latitude: 12.899305,
//                 longitude: 77.634118,
//                 latitudeDelta: 0.0922,
//                 longitudeDelta: 0.0421
//             },
//             markers: mini
//         }
//     }    

//     createNewRide(){
//         ToastAndroid.show('Trip created successfully!', ToastAndroid.SHORT);
//     }

//     componentDidMount() {
//       Geolocation.getCurrentPosition((position) => {
//             var lat = parseFloat(position.coords.latitude)
//             var long = parseFloat(position.coords.longitude)
//             var initialRegion = {
//                 latitude: lat,
//                 longitude: long,
//                 latitudeDelta: 0.0922,
//                 longitudeDelta: 0.0421
//             }
//             this.setState({ initialRegion: initialRegion })
//         },
//             (error) => alert(JSON.stringify(error)),
//             { enableHighAccuracy: true, timeout: 20000, maximumAge: 1000 });
//     }

//     render() {
//         return (
//             <View style={styles.container}>
//                 <View style={styles.mapContainer}>
//                     <MapView style={styles.map}
//                         initialRegion={this.state.initialRegion}
//                         showsUserLocation={true}>
//                             <Polyline coordinate={{ "latitude": this.state.initialRegion.latitude, "longitude": this.state.initialRegion.longitude }} strokeWidth={2} />
//                         {!!this.state.initialRegion.latitude && !!this.state.initialRegion.longitude && <Marker

//                             coordinate={{ "latitude": this.state.initialRegion.latitude, "longitude": this.state.initialRegion.longitude }}
//                             title={"Your Location"}
//                         />}
//                         {this.state.markers.map((marker, index) => (
//                             <Marker
//                             key={index}
//                             coordinate={marker.coordinates}
//                             title={marker.title}
//                             anchor={{x:0,y:0}}
//                             >
//                                 <View style={styles.pinView}>
//                                     <Text style={styles.pinText}>{marker.title}</Text>
//                                     {/* <Image style={styles.pinImage} source={require('../icons/sedan.png')}></Image> */}
//                                 </View>
//                             </Marker>
//                         ))}
//                     </MapView>

//                     {/* <Callout>
//                         <View style={styles.calloutView} >
//                             <GooglePlacesAutocomplete
//                                 placeholder='Enter Destination'
//                                 minLength={2}
//                                 autoFocus={false}
//                                 returnKeyType={'default'}
//                                 fetchDetails={true}
//                                 renderDescription={row => row.description} // custom description render
//                                 onPress={(data, details = null) => { // 'details' is provided when fetchDetails = true
//                                     console.log(data, details);
//                                 }}
//                                 getDefaultValue={() => ''}
//                                 query={{
//                                     key: 'gogole-api-key',
//                                     language: 'en', // language of the results
//                                     //types: '(cities)' // default: 'geocode'
//                                 }}
//                                 styles={{
//                                     textInputContainer: {
//                                         width: '100%'
//                                     },
//                                     textInput: {
//                                         fontSize: 16,
//                                     },
//                                     predefinedPlacesDescription: {
//                                         color: 'white'
//                                     },
//                                 }}
//                                 currentLocation={false}
//                             />
//                         </View>
//                     </Callout> */}
//                 </View>


//                 <View style={styles.tabsContainer}>
//                     <View style={styles.tabContainer}>
//                         <View style={styles.buttonContainer}>
//                             <TouchableOpacity onPress={() => this.setState({ markers: mini })}>
//                                 <Text style={styles.minuteText}></Text>
//                                 {/* <Image source={require('../icons/mini.png')}></Image> */}
//                                 <Text style={styles.cabTypeText}>Mini</Text>
//                             </TouchableOpacity>
//                         </View>
//                         <View style={styles.buttonContainer}>
//                             <TouchableOpacity onPress={() => this.setState({ markers: sedan })}>
//                                 <Text style={styles.minuteText}></Text>
//                                 {/* <Image source={require('../icons/sedan.png')}></Image> */}
//                                 <Text style={styles.cabTypeText}>Sedan</Text>
//                             </TouchableOpacity>
//                         </View>
//                         <View style={styles.buttonContainer}>
//                             <TouchableOpacity onPress={() => this.setState({ markers: suv })}>
//                                 <Text style={styles.minuteText}></Text>
//                                 {/* <Image source={require('../icons/suv.png')}></Image> */}
//                                 <Text style={styles.cabTypeText}>SUV</Text>
//                             </TouchableOpacity>
//                         </View>
//                     </View>

//                     <View style={styles.rideContainer}>
//                         <TouchableOpacity onPress={() => this.createNewRide()}><Text style={styles.cabTypeButton}>Ride Now</Text>
//                         </TouchableOpacity>
//                     </View>
//                 </View>


//             </View>
//         );
//     }
// }

// const styles=StyleSheet.create({
//     container:{
//         flex: 1,
//       },mapContainer: {
//         flex: 4,
//       },
//       map: {
//         flex: 1,
//         width: Dimensions.get("window").width,
//         height: Dimensions.get("window").height,
//       },
//       tabsContainer: {
//         flex: 1,
//       },
//       tabContainer: {
//         flex: 1.5,
//         flexDirection: 'row',
//         margin: 1,
//         backgroundColor: 'black',
//       },
//       buttonContainer:{
//         flex: 1,
//         justifyContent: 'center',
//         alignItems: 'center',
//         borderColor :'black',
//         borderWidth: 0.5,
//         margin: 3,
//         borderRadius: 150,
//         backgroundColor: 'white',
//       }, 
//       rideContainer:{
//         flex: 1,
//         justifyContent: 'center',
//         alignItems: 'center',
//         borderColor :'black',
//         borderWidth: 1,
//         backgroundColor: 'white',
//       },
//       cabTypeText: {
//         color: 'black',
//         fontSize: 10,
//       },
//       minuteText: {
//         color: 'black',
//         fontSize: 12
//       },
//       pinView: {
//         width: 40,
//         height: 40,
//         borderRadius: 100,
//         backgroundColor: 'blue',
//     },
//     pinText: {
//         flex:1,
//         color: 'white',
//         textAlign: 'center',
//         fontSize: 14,
//     },
//     pinImage: {
//       flex:1,
//     },
//     calloutView: {
//       //borderRadius: 10,
//       width: "75%",
//       marginLeft: "35%",
//       marginRight: "20%",
//       marginTop: "20%"
//     },
// });
