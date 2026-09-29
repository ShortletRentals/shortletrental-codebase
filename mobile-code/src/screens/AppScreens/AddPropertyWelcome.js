import React, { useState } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import LinearGradient from "react-native-linear-gradient";
import MapView from 'react-native-maps';
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { useIsFocused, useNavigation, useRoute } from "@react-navigation/native";
import { useEffect } from "react"; 
import { BackHandler } from "react-native";



const AddPropertyWelcome = (props) => {
    const route = useRoute()

const [count1,setCount1] = useState(1)
const [count2,setCount2] = useState(1)
const [count3,setCount3] = useState(1)
const [count4,setCount4] = useState(1)
const [count5,setCount5] = useState(1)
const [userId, setUserId] = useState(route?.params?.userId)
const [propertyId, setPropertyId] = useState(route?.params?.propertyId)
const [categoryId, setCategoryId] = useState(route?.params?.categoryId)
const counter1 = ()=>{
    if(count1>0){
        setCount1(count1-1)
    }
}
const counter2 = ()=>{
    if(count2>0){
        setCount2(count2-1)
    }
}
const counter3 = ()=>{
    if(count3>0){
        setCount3(count3-1)
    }
}
const counter4 = ()=>{
    if(count4>0){
        setCount4(count4-1)
    }
}
const counter5 = ()=>{
    if(count5>0){
        setCount5(count5-1)
    }
}


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

const handleValidation =()=>{
   
      if (count2==0){
        Toast.show({
            type:'error',
            text1:"Shortlet",
            text2:"Please add beds",
            
            
        })
    } else if (count3==0){
        Toast.show({
            type:'error',
            text1:"Shortlet",
            text2:"Please add bedrooms",
            
            
        })
    } else if (count4==0){
        Toast.show({
            type:'error',
            text1:"Shortlet",
            text2:"Please add bathrooms",
            
            
        })
    } else {
        props.navigation.navigate('AddPropertyLocated1',{
            propertyId: route?.params?.propertyId, userId: route?.params?.userId,
                 categoryId: route?.params?.categoryId,
                 address: route?.params?.address, latitude: route?.params?.latitude, longitude: route?.params?.longitude,
                  countryId: route?.params?.countryId,
                 provinceId: route?.params?.propertyId, cityId: route?.params?.cityId, areaId: route?.params?.areaId,
                  postalCode:route?.params?.postalCode,streetName:route?.params?.streetName,
                  streetType:route?.params?.streetType,selectFloor:route?.params?.selectFloor,stairs:route?.params?.stairs,
                elevator:route?.params?.elevator,apertNo:route?.params?.apertNo,beds:count2,bedrooms:count3,bathrooms:count4,kitchen:count1
        })
        // props.navigation.navigate('AddPropertyOffer',{
        //     propertyId: route.params.propertyId, userId: route.params.userId,
        //          categoryId: route.params.categoryId,
        //          address: route.params.address, latitude: route.params.latitude, longitude: route.params.longitude,
        //           countryId: route.params.countryId,
        //          provinceId: route.params.propertyId, cityId: route.params.cityId, areaId: route.params.areaId,
        //           postalCode:route.params.postalCode,streetName:route.params.streetName,
        //           streetType:route.params.streetType,selectFloor:route.params.selectFloor,stairs:route.params.stairs,
        //         elevator:route.params.elevator,apertNo:route.params.apertNo,beds:count2,bedrooms:count3,bathrooms:count4,kitchen:count1
        // })
    }

}


    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Add Property'} onPress={()=>props.navigation.goBack()} />
            <Text style={{ marginTop: 20, fontSize: 18, fontWeight: '500', marginHorizontal: 20,color:color.primaryColorBlack }}>
                {/* How many guests would you like to welcome? */}
                What is the description of the accommodation?
                </Text>
            <View style={{flex:1}}  >
            <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20, borderBottomWidth: 1, borderBottomColor: color.appTextColor }}>
                    <View style={{  marginVertical: 20 }}>
                        <Text style={{ fontSize: 15, fontWeight: '400' ,color:color.primaryColorBlack}}>Beds</Text>

                    </View>
                    <View style={{ flexDirection: 'row', marginTop: 10,  marginVertical: 10 }}>
                    <TouchableOpacity onPress={counter2} style={styles.AddingBackground}><Image source={Images.minusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                        <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center',alignSelf:'center' }}><Text style={{color:color.primaryColorBlack}}>{count2}</Text></View>
                        <TouchableOpacity onPress={()=>setCount2(count2+1)} style={styles.AddingBackground}><Image source={Images.plusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                    </View>

                </View>
             

               

                <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20, borderBottomWidth: 1, borderBottomColor: color.appTextColor }}>
                    <View style={{ marginVertical: 20 }}>
                        <Text style={{ fontSize: 15, fontWeight: '400',color:color.primaryColorBlack }}>Bedrooms</Text>

                    </View>
                    <View style={{ flexDirection: 'row', marginTop: 10,  marginVertical: 10 }}>
                    <TouchableOpacity onPress={counter3} style={styles.AddingBackground}><Image source={Images.minusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                        <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center',alignSelf:'center' }}><Text style={{color:color.primaryColorBlack}}>{count3}</Text></View>
                        <TouchableOpacity onPress={()=>setCount3(count3+1)} style={styles.AddingBackground}><Image source={Images.plusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                    </View>

                </View>

                <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20,borderBottomWidth: 1, borderBottomColor: color.appTextColor  }}>
                    <View style={{ marginVertical: 20 }}>
                        <Text style={{ fontSize: 15, fontWeight: '400',color:color.primaryColorBlack }}>Bathrooms with shower</Text>

                    </View>
                    <View style={{ flexDirection: 'row', marginTop: 10, marginVertical: 10 }}>
                    <TouchableOpacity onPress={counter4} style={styles.AddingBackground}><Image source={Images.minusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                        <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center',alignSelf:'center' }}>
                            <Text style={{color:color.primaryColorBlack}}>{count4}</Text>
                            </View>
                        <TouchableOpacity onPress={()=>setCount4(count4+1)} style={styles.AddingBackground} ><Image source={Images.plusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                    </View>

                </View>
                <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20, borderBottomWidth: 1, borderBottomColor: color.appTextColor,marginVertical:5 }}>
                    <View style={{  marginVertical: 10 }}>
                        <Text style={{ fontSize: 15, fontWeight: '400',color:color.primaryColorBlack }}>Bathrooms with bathtub</Text>

                    </View>
                    <View style={{ flexDirection: 'row', marginTop: 10,  marginVertical: 10 }}>
                        <TouchableOpacity onPress={counter1} style={styles.AddingBackground}><Image source={Images.minusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                        <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center',alignSelf:'center' }}><Text style={{color:color.primaryColorBlack}}>{count1}</Text></View>
                        <TouchableOpacity onPress={()=>setCount1(count1+1)} style={styles.AddingBackground}><Image source={Images.plusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                    </View>

                </View>
                <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginHorizontal: 20, borderBottomWidth: 1, borderBottomColor: color.appTextColor,marginVertical:0 }}>
                    <View style={{  marginVertical: 10 }}>
                        <Text style={{ fontSize: 15, fontWeight: '400',color:color.primaryColorBlack }}>Kitchens</Text>

                    </View>
                    <View style={{ flexDirection: 'row', marginTop: 10,  marginVertical: 10 }}>
                        <TouchableOpacity onPress={counter5} style={styles.AddingBackground}><Image source={Images.minusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                        <View style={{ height: 30, width: 30, alignItems: 'center', justifyContent: 'center',alignSelf:'center' }}><Text style={{color:color.primaryColorBlack}}>{count5}</Text></View>
                        <TouchableOpacity onPress={()=>setCount5(count5+1)} style={styles.AddingBackground}><Image source={Images.plusIcon} style={{height:30,width:30}} /></TouchableOpacity>
                    </View>

                </View>


               
                 <View style={{  flex: 1,justifyContent:'flex-end' }}>
                    <View style={{ padding:20,backgroundColor:color.appTextBackgoundColor,flexDirection: 'row', justifyContent: 'space-between',  marginTop: 20 }}>
                        <TouchableOpacity onPress={()=>props.navigation.goBack()}style={{ backgroundColor: color.appBlueColor, borderRadius: 10 }}><Text style={{ fontSize: 18, color: color.appWhiteColor, marginHorizontal: 40, marginVertical: 20, fontWeight: '600' }}>Back</Text></TouchableOpacity>
                        <View>
                              <TouchableOpacity onPress={()=>handleValidation()}>
                            <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={styles.linearGradient, { borderRadius: 10 }} >
                                <Text style={{ marginHorizontal: 40, marginVertical: 20, color: color.appWhiteColor, fontWeight: '600', fontSize: 18 }} >
                                    Next
                                </Text>
                            </LinearGradient>
                                </TouchableOpacity>
                        </View>
                    </View>
                </View>




            </View>
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
    AddingBackground: { alignSelf:'center' },

    linearGradient: {
        flex: 1,
        paddingLeft: 15,
        paddingRight: 15,

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

});




export default AddPropertyWelcome;