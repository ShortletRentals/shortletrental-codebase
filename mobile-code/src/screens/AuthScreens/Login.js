import React from "react";
import { View, Image, Text, ImageBackground, TouchableOpacity } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";


const Login = (props) => {
    return (
        <View style={commonStyles.container}>
            <ImageBackground
                source={Images.BackgroundImage}
                style={{ flex: 1 }}
            >
                <ImageBackground source={Images.whiteImage} style={{
                    width: width / 0.9,
                    height: height / 2.2,
                    resizeMode: 'stretch',
                    transform: [{ rotate: '270deg' }]

                }} >
                    <View style={{ justifyContent: 'flex-start', flex: 1 }}>
                        <Image source={Images.House} style={{
                            width: width / 1.4, height: height / 3.5, resizeMode: 'stretch',
                            transform: [{ rotate: '90deg' },],
                        }} />
                    </View>

                </ImageBackground>
                <View style={{alignItems:"center",marginTop:20}}><Text style={{
                    fontSize: width*(30/375),
                    marginTop:width*(35/375),
                    fontWeight: "bold",alignItems:"center", color: "white"
                }}>Find Property Here</Text>
                <Text style={{ fontSize: width*(20/375), fontWeight: "normal", color: "white", textAlign: "center" }}>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</Text>
               

                <TouchableOpacity onPress={()=>props.navigation.navigate('SignUp')} style={{marginTop:20,
                    backgroundColor:color.appBlueColor,
                    width:width-30,
                    padding:10,
                    borderRadius:10,
                    justifyContent:'center'
                }}>
                    <View style={{
                        position:'absolute',
                        flex:1,
                    }}>
                    <Image source={Images.whiteDot} style={{paddingLeft:70,
                        width: 20, height: 20, resizeMode:'contain',
                    }} />
                    </View>

                    <Text style={{textAlign:"center",fontSize: width*(25/375),color:color.appWhiteColor}}>Sign Up</Text>
                </TouchableOpacity>
                
                <TouchableOpacity onPress={()=>props.navigation.navigate('Logins')} style={{marginTop:20,
                    backgroundColor:color.appYellowColor,
                    width:width-30,
                    padding:10,
                    borderRadius:10,
                    justifyContent:'center'
                }}>
                    <View style={{
                        position:'absolute',
                        flex:1,
                    }}>
                    <Image source={Images.whiteDot} style={{paddingLeft:70,
                        width: 20, height: 20, resizeMode:'contain',
                    }} />
                    </View>

                    <Text style={{textAlign:"center",fontSize: width*(25/375),color:color.appWhiteColor}}>Login</Text>
                </TouchableOpacity>
                
                
                </View>

                
            </ImageBackground>

        </View>
    )
}

export default Login;