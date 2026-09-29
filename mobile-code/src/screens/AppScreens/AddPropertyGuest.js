import React, { useState, useEffect } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView, ActivityIndicator } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import { SafeAreaView } from "react-native-safe-area-context";
import LinearGradient from "react-native-linear-gradient";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import BecomeAhostTab from "../../navigators/BecomeAhostTab";
import axios from "axios";
import { useRoute } from "@react-navigation/native";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { getAllTypes } from '../../network/Webconstant';
import { BackHandler } from "react-native";






const renderData = [
    { image: Images.House1, title: 'Flat' },
    { image: Images.House2, title: 'House' },
    { image: Images.House3, title: 'Secondary unit' },
    { image: Images.House4, title: 'Unique space' },
    { image: Images.House5, title: 'Bed & Breakfast' },
    { image: Images.House6, title: 'Boutique hotel' }


]

let propertyId = ""
let selectedIndex = -1
const AddPropertyguest = (props) => {
    const route = useRoute()
    const [isSelect, setIsSelect] = useState(null)
    const [propData, setPropData] = useState([])
    const [userId, setUserID] = useState("")
    const [loader, setLoader] = useState(false);

    const handler = () => {
        props.navigation.goBack()
      }
      useEffect(() => {
        const backAction = () => {
          handler()
          return true;
        };
    
        const backHandler = BackHandler.addEventListener(
          'hardwareBackPress',
          backAction,
        );
    
        return () => backHandler.remove();
      }, [props])



    useEffect(() => {
        setLoader(true)

        getUserId()
        // console.log('from======',route.params.id)



        axios({
            method: "get",
            url: getAllTypes,
            // headers: { "content-type": "application/x-www-form-urlencoded" }
        })
            .then((response) => {
                if (response?.data?.status == true) {
                 
                    setPropData(response.data.data)
                    setLoader(false)

                  

                } else {
                    console.log("api error", response);
                    setLoader(false)

                }
            })
            .catch((e) => {
                setLoader(false)

            });


    }, [])


    const getUserId = async () => {
        try {
            const myUserId = await AsyncStorage.getItem("user_id");

            // console.log("my user id======", myUserId)
            setUserID(myUserId)


        } catch (error) {
            console.log(error);
        }
    };




    const handleValidation = () => {
        if (isSelect == null) {
            Toast.show({
                type: 'error',
                text1: "Shortlet",
                text2: "Please select what kind of space guest have?",


            })
        } else {
            // console.log("---", propertyId)
            props.navigation.navigate('AddProperty', { propertyId: propertyId, userId: userId })

        }
    }
    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Add Property'} onPress={() => props.navigation.goBack()} />
            {/* <Image source={{uri:"https://d1o88e3pxnk9ri.cloudfront.net/uploads/property/chalet.jpg"}} style={{height:100,width:100,tintColor:'red'}}/> */}
            {
                loader ? (
                    <View style={{ justifyContent: 'center', marginVertical:"40%",}}>
                        <ActivityIndicator size="large" color="#F99428" />
                    </View >
                ) : <ScrollView showsVerticalScrollIndicator={false} style={{ flex: 1 }}>
                    <Text style={{ marginHorizontal: 20, marginTop: 20, fontSize: 17, fontWeight: '500', color: color.primaryColorBlack, marginBottom: 10 }}>
                        What kind of space will guest have?
                         </Text>
                    {
                        propData.map((item, index) => {
                            // console.log('-----item',item.image.match('shortletrental') == 'shortletrental');
                            return (
                                <TouchableOpacity onPress={() => [setIsSelect(index), propertyId = item?.id]} style={[styles.flatView, { borderColor: isSelect !== index   ? color.appTextColor : color.appYellowColor }]}>
                                    <Text style={{ marginHorizontal: 10, color: color.primaryColorBlack, fontWeight: '500' }}>{item.name}</Text>
                                    <Image source={{ uri: item?.image }} style={{ height: 50, width: 50, resizeMode: "cover", borderTopRightRadius: 5, borderBottomRightRadius: 5,
                                    // tintColor:item.image.match('https://d1o88e3pxnk9ri.cloudfront.net/uploads/property/') == item.image ? 'black' : 'white'
                                     }} />
                                </TouchableOpacity>
                            )
                        })}
                    <View style={{ flex: 1, justifyContent: 'flex-end' }}>
                        <View style={{ padding: 20, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', justifyContent: 'space-between', marginTop: 20 }}>
                            <TouchableOpacity onPress={() =>
                                props.navigation.goBack()
                            } style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}><Text style={{ fontSize: 18, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                            <View>
                                <TouchableOpacity onPress={() => handleValidation()
                                    // props.navigation.navigate('AddProperty')
                                }>
                                    <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={[styles.linearGradient, { borderRadius: 10 }]} >
                                        <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 18 }} >
                                            Next
                                        </Text>
                                    </LinearGradient>
                                </TouchableOpacity>
                            </View>
                        </View>
                    </View>

                </ScrollView>
            }

            {/* <BecomeAhostTab props={props}/> */}


        </View>
    )
}

const styles = StyleSheet.create({
    input: {
        height: 40,
        margin: 12,
        padding: 20,
        backgroundColor: color.appTextBackgoundColor,
        borderRadius: 4
    },
    container: {
        marginRight: 10, fontSize: 20, color: color.appWhiteColor, fontWeight: '600',
    },
    linearGradient: {
        flex: 1,
        // paddingLeft: 15,
        // paddingRight: 15,

    },
    buttonText: {
        fontSize: 18,
        fontFamily: 'Gill Sans',
        textAlign: 'center',
        marginVertical: 20,
        color: '#ffffff',
        marginHorizontal: 50,

        justifyContent: 'center',
        alignSelf: "center",
        backgroundColor: 'transparent',
    },
    flatView: { marginHorizontal: 20, marginTop: 10, flexDirection: 'row', justifyContent: 'space-between', borderRadius: 8, borderWidth: 1, alignItems: 'center' }

});


export default AddPropertyguest;