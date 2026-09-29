import React, { useState } from "react";
import { View,Pressable, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView, Modal } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import { SafeAreaView } from "react-native-safe-area-context";


const renderData = [
    { image: Images.calenderIcon, },
    { image: Images.House, },
    { image: Images.fbIcon, },
    { image: Images.fbIcon, },
    { image: Images.fbIcon, }
]
const Photos = (props) => {
    const [modalVisible,setModalVisible]=useState(false)
    return (
        <View style={commonStyles.container}>
          

            <Header props={props} Heading={'Photos'} onPress={()=>props.navigation.navigate('AddPropertyPhotos')} />
            <View >
                {
                    renderData.map((item) => {
                        return (
                            <View style={{ flexDirection: 'row' }}>

                                <Image source={{ uri: 'https://thumbs.dreamstime.com/b/software-testing-internet-business-technology-concept-143071525.jpg' }} style={{ width: width / 2 }}></Image>

                                <TouchableOpacity onPress={()=>setModalVisible(true)} >
                                    <Image source={Images.HotelImage} style={{ width: width / 2 }}></Image>
                                </TouchableOpacity>
                            </View>
                        )
                    })}
            </View>
            <View style={commonStyles.centeredView}>
                <Modal
                    animationType="slide"
                    transparent={true}
                    visible={modalVisible}
                    onRequestClose={() => {
                        Alert.alert("Modal has been closed.");
                        setModalVisible(!modalVisible);
                    }}
                >
                    <View style={commonStyles.centeredView}>
                        <View style={{width:width,flex:1,justifyContent:"center"}}>
                        
                                <ImageBackground source={{ uri: 'https://thumbs.dreamstime.com/z/classic-house-flower-garden-751996.jpg' }} style={{width:width-50,height:height/2.5,borderRadius:10,alignSelf:'center'}}>
                                <Pressable
                               
                               onPress={() => setModalVisible(!modalVisible)}
                           >
                               <Image source={Images.cancelImageIcon} style={{height:30,width:30,marginRight:10,marginTop:10,alignSelf:'flex-end'}}/>
                           </Pressable>
                                    </ImageBackground>
                           
                        
                        </View>
                    </View>
                </Modal>
                <Pressable
                    style={[commonStyles.button, commonStyles.buttonOpen]}
                    onPress={() => setModalVisible(true)}
                >
                    <Text style={commonStyles.textStyle}>Show Modal</Text>
                </Pressable>
            </View>
        </View>
    )
}

export default Photos;