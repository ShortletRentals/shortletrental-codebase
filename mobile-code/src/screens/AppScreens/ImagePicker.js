import React, { useState } from "react";
import { View, Text, Button, Image, Modal, StyleSheet, TouchableOpacity } from 'react-native'
import { launchCamera, launchImageLibrary } from 'react-native-image-picker';
import { colors } from "./colors";

 const ImagePicker = ({visible, onClose,}) => {
    const CameraAction = () => {
        let options = {
            storageOption:{
                path:'images',
                mediaType:'photo',
            },
            includeBase64: true,
        };
        launchCamera(options, response => {
            console.log('Response =',response);
            if(response.didCancel){
                console.log('User cancelled image picker')
            }else if(response.error){
                console.log('ImagePicker Error:',response.error);
            }else if(response.CustomButton){
                console.log('User tapped custom button:',response.CustomButton)
            }else{
                const source = {uri: 'data:image/jpeg;base64,' + response?.assets[0].base64}
                onClose(source)
                // setImageUri(source)
            }
        })
    }

    const GalleryAction = () => {
        let options = {
            storageOption:{
                path:'images',
                mediaType:'photo',
            },
            includeBase64: true,
        };
        launchImageLibrary(options, response => {
            console.log('Response =',response);
            if(response.didCancel){
                console.log('User cancelled image picker')
            }else if(response.error){
                console.log('ImagePicker Error:',response.error);
            }else if(response.CustomButton){
                console.log('User tapped custom button:',response.CustomButton)
            }else{

                const source = {uri: 'data:image/jpeg;base64,' + response?.assets[0]?.base64}
                onClose(source)
                // setImageUri(source)
            }
        })
    }

    return (
        <Modal 
            visible={visible}
            transparent={true}
            onRequestClose={onClose}
        >
            <TouchableOpacity activeOpacity={1} onPress={onClose} style={styles.body}>
                <TouchableOpacity style={styles.popupView} activeOpacity={1}>
                    <Text style={styles.txt}>Select Image From</Text>
                    <TouchableOpacity onPress={GalleryAction} style={styles.btn}>
                        <Text style={styles.txt}>Gallery</Text>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={CameraAction} style={styles.btn}>
                        <Text style={styles.txt}>Camera</Text>
                    </TouchableOpacity>
                </TouchableOpacity>
            </TouchableOpacity>
        </Modal>
    ) 
}
export default ImagePicker
const styles = StyleSheet.create({
    body: {
        flex: 1,
        backgroundColor: colors.black5,
        justifyContent: 'center',
        alignItems: 'center'
    },
    popupView: {
        backgroundColor: colors.white,
        borderRadius: 20,
        padding: 40,
        width: '80%'

    },
    txt: {
        fontSize: 20,
        fontWeight: '600',
        color: colors.background
    },
    btn: {
        height: 46,
        borderRadius: 8,
        borderWidth: 2,
        borderColor: colors.background,
        width: 150,
        marginTop: 20,
        justifyContent: 'center',
        alignItems: 'center',
        alignSelf: 'center'
    }
})