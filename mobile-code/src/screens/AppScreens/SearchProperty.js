import React, { useState } from "react";
import { View, Image, Text,Alert,StyleSheet, ImageBackground, TouchableOpacity, _Text, ScrollView } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import CheckBox from '@react-native-community/checkbox';

const SearchProperty = (props) => {
    const [RentalName, setRentalName] = useState('');
    const [PerNight, setPerNight] = useState('');
    const [Booking, setBooking] = useState('');
    const [toggleCheckBox, setToggleCheckBox] = useState(false)
   
    return (
        <View style={commonStyles.container}>

            
<Header props={props} Heading={'Search'} onPress={()=>props.navigation.navigate('BecomeaHostHome')}/>
            <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
                

                <Text style={{ marginLeft: 25,marginTop:width*(10/375),color:color.primaryColorBlack,fontWeight:'600'}}>Property</Text>
                <TextView
                    placeholder={'Rental Name'}
                    value={RentalName}
                    onChangeText={(e) => setRentalName(e)}
                    isNumeric={false}
                />

<Text style={{ marginLeft: 25,marginTop:width*(10/375),color:color.primaryColorBlack,fontWeight:'600'}}>Price</Text>
                <TextView
                    placeholder={'per night'}
                    value={PerNight}
                    onChangeText={(e) => setPerNight(e)}
                    isNumeric={false}
                />
<Text style={{ marginLeft: 25,marginTop:width*(10/375),color:color.primaryColorBlack,fontWeight:'600'}}>Booking</Text>
                <TextView
                    placeholder={'Next Booking'}
                    value={Booking}
                    onChangeText={(e) => Booking(e)}
                    isNumeric={false}
                />

                

<View style={{flex:1,justifyContent:'flex-end',marginBottom:20}}>

                <TouchableOpacity  style={{
                    marginTop: 10,
                    backgroundColor: color.appBlueColor,
                    width: width - 30,
                    padding: 10,
                    borderRadius: 10,
                    justifyContent: 'center',
                    marginLeft: 15,
                    height:50
                }}>
                    <View style={{
                        position: 'absolute',
                        flex: 1,
                    }}>
                        <Image source={Images.whiteDot} style={{
                            paddingLeft: 70,
                            width: 25, height: 25, resizeMode: 'contain',
                        }} />
                    </View>
                    <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Search</Text>
                </TouchableOpacity>

                </View>
            </ScrollView>
        </View>


    )
}

export default SearchProperty;

