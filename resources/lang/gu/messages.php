<?php

// return [
//     'ask_name' => "👉 *તમારું નામ શું છે?*",
//     'thanks_name' => "✅ આભાર, :name!",

//     'ask_email' => "📧 કૃપા કરીને તમારું ઇમેઇલ સરનામું દાખલ કરો.",
//     'thanks_email' => "✅ આભાર! તમારું ઇમેઇલ (:email) સાચવી લેવામાં આવ્યું છે.",

//     'ask_gender' => "👩👨 કૃપા કરીને તમારું *લિંગ* પસંદ કરો:",
//     'thanks_gender' => "✅ લિંગ *:gender* તરીકે સાચવવામાં આવ્યું છે.",
//     'gender_male' => "પુરુષ",
//     'gender_female' => "સ્ત્રી",
//     'gender_other' => "અન્ય",

//     'ask_marital_status' => "💍 કૃપા કરીને તમારી વૈવાહિક સ્થિતિ પસંદ કરો:",
//     'thanks_marital_status' => "✅ વૈવાહિક સ્થિતિ *:status* તરીકે સાચવી છે.",
//     'status_single' => "અવિવાહિત",
//     'status_married' => "વિવાહિત",
//     'status_divorced' => "છૂટાછેડા લીધેલ",

//     'ask_dob' => "📅 કૃપા કરીને તમારી જન્મ તારીખ `DD-MM-YYYY` ફોર્મેટમાં દાખલ કરો:",
//     'dob_placeholder' => "DD-MM-YYYY",
//     'invalid_dob_format' => "❌ અમાન્ય તારીખ ફોર્મેટ. કૃપા કરીને `DD-MM-YYYY` ફોર્મેટમાં દાખલ કરો, જેમ કે *13-07-1998*.",
//     'thanks_dob' => "✅ જન્મ તારીખ *:dob* તરીકે સાચવી છે.",

//     'ask_state' => "🏞️ કૃપા કરીને તમારું *રાજ્ય* પસંદ કરો:",
//     'thanks_state' => "✅ રાજ્ય *:state* તરીકે સાચવ્યું છે.",

//     'ask_city' => "🏙️ કૃપા કરીને તમારું *શહેર* પસંદ કરો:",
//     'thanks_city' => "✅ શહેર *:city* તરીકે સાચવ્યું છે.",
//     'select_state_first' => "મહેરબાની કરીને પહેલાં રાજ્ય પસંદ કરો",

//     'ask_mother_tongue' => "🗣️ *કૃપા કરીને તમારી માતૃભાષા પસંદ કરો:*",
//     'thanks_mother_tongue' => "✅ *:tongue* તરીકે સાચવી લેવાઈ છે. કૃપા કરીને રાહ જુઓ...",

//     'tongue_hindi' => 'હિન્દી',
//     'tongue_marathi' => 'મરાઠી',
//     'tongue_gujarati' => 'ગુજરાતી',
//     'tongue_punjabi' => 'પંજાબી',
//     'tongue_tamil' => 'તમિલ',
//     'tongue_telugu' => 'તેલુગુ',

//     'other' => 'અન્ય',
//     'type_mother_tongue' => '✍️ કૃપા કરીને તમારી માતૃભાષા ટાઈપ કરો:',

//     'ask_religion' => "🙏 કૃપા કરીને તમારું *ધર્મ* પસંદ કરો:",
//     'thanks_religion' => "✅ ધર્મ *:religion* તરીકે સાચવ્યું છે.",
//     'religion_hindu' => 'હિન્દુ',
//     'religion_muslim' => 'મુસ્લિમ',
//     'religion_christian' => 'ઈસાઈ',
//     'religion_sikh' => 'સીખ',
//     'religion_buddhist' => 'બૌદ્ધ',
//     'religion_jain' => 'જૈન',
//     'other' => 'અન્ય',


//     'ask_caste' => "🧬 કૃપા કરીને તમારી *જાતિ* પસંદ કરો અથવા લખો:",
//     'thanks_caste' => "✅ જાતિ *:caste* તરીકે સંગ્રહિત કરવામાં આવી છે.",
//     'caste_brahmin' => 'બ્રાહ્મણ',
//     'caste_kshatriya' => 'ક્ષત્રિય',
//     'caste_vaishya' => 'વૈશ્ય',
//     'caste_shudra' => 'શૂદ્ર',
//     'other' => 'અન્ય',


//     'ask_education' => "🎓 કૃપા કરીને તમારું *સૌથી ઊંચું શિક્ષણ સ્તર* પસંદ કરો:",
//     'thanks_education' => "✅ શિક્ષણ સ્તર *:education* તરીકે સેવ કરવામાં આવ્યું છે.",
//     'education_highschool' => 'હાઈ સ્કૂલ',
//     'education_diploma' => 'ડિપ્લોમા',
//     'education_bachelor' => 'સ્નાતક',
//     'education_master' => 'સ્નાતકોત્તર',
//     'education_phd' => 'પી.એચ.ડી.',
//     'other' => 'અન્ય',

//     'ask_education_field' => "🔬 કૃપા કરીને તમારું *અભ્યાસ ક્ષેત્ર* દાખલ કરો (જેમ કે, એન્જિનિયરિંગ, આર્ટ્સ, કોમર્સ):",
//     'thanks_education_field' => "✅ અભ્યાસ ક્ષેત્ર *:field* તરીકે સેવ કરવામાં આવ્યું છે.",
//     'field_engineering' => 'એન્જિનિયરિંગ',
//     'field_arts' => 'આર્ટ્સ',
//     'field_commerce' => 'કોમર્સ',
//     'field_science' => 'સાયન્સ',
//     'other' => 'અન્ય',

//     'ask_working_sector' => "💼 *કૃપા કરીને તમારું કામકાજ ક્ષેત્ર પસંદ કરો:*",
//     'thanks_working_sector' => "✅ કામકાજ ક્ષેત્ર *:sector* તરીકે સેવ થયું છે.",
//     'sector_private' => 'ખાનગી',
//     'sector_government' => 'સરકારી',
//     'sector_business' => 'વ્યવસાય',
//     'sector_freelance' => 'ફ્રીલાન્સ',
//     'sector_student' => 'વિદ્યાર્થી',
//     'sector_not_working' => 'કામમાં નથી',

//     'ask_profession' => "💼 *તમારું વ્યવસાય દાખલ કરો* (ઉદાહરણ: સોફ્ટવેર એન્જિનિયર, ડોકટર, શિક્ષક):",
//     'thanks_profession' => "✅ વ્યવસાય *:profession* તરીકે સેવ થયો છે.",

//     'profession_software_engineer' => 'સોફ્ટવેર એન્જિનિયર',
//     'profession_doctor' => 'ડોકટર',
//     'profession_teacher' => 'શિક્ષક',
//     'profession_businessman' => 'વ્યવસાયિક',
//     'profession_student' => 'વિદ્યાર્થી',
//     'profession_other' => 'અન્ય',

//     'ask_mobile' => "📱 *મહેરબાની કરીને તમારો 10 અંકનો મોબાઇલ નંબર દાખલ કરો* (6–9થી શરૂ થાય):",
//     'invalid_mobile' => "❌ અમાન્ય નંબર. કૃપા કરીને *માન્ય 10-અંકનો* મોબાઇલ નંબર દાખલ કરો.",
//     'thanks_mobile' => "✅ મોબાઇલ નંબર *:mobile* તરીકે સંગ્રહાયો છે.",

//     'ask_profile_photo' => "📸 કૃપા કરીને તમારી પ્રોફાઇલ તસવીર મોકલશો (image attachment તરીકે):",
//     'skip_photo' => 'ફોટો સ્કિપ કરો',
//     'upload_another_photo' => 'બીજી ફોટો અપલોડ કરો',
//     'profile_photo_skipped' => "✅ સમજી લીધું! ચાલો આગળ વધીએ.",
//     'profile_photo_invalid_text' => "❌ કૃપા કરીને માન્ય તસવીર મોકલો અથવા *Done* ટૅપ કરો.",
//     'profile_photo_invalid' => "❌ કૃપા કરીને માન્ય તસવીર મોકલશો.",
//     'profile_photo_process_failed' => "❌ તસવીર પ્રક્રિયા કરી શકાઈ નહીં.",
//     'profile_photo_save_failed' => "❌ તસવીર સંગ્રહવામાં નિષ્ફળતા.",
//     'profile_photo_uploaded' => "🖼️ તમારી ગેલેરીમાં તસવીર ઉમેરાઈ ગઈ છે!",

//     'ask_diet' => '🍽️ *તમારો આહાર પસંદગી શું છે?*',
//     'invalid_diet' => '❌ કૃપા કરીને શાકાહારી, માંસાહારી અથવા બંનેમાંથી પસંદ કરો.',
//     'saved_diet' => '✅ તમારું આહાર પસંદગી *:diet* તરીકે સાચવાઈ ગઈ છે.',

//     'ask_diet' => '🍽️ *आपका भोजन विकल्प क्या है?*',
//     'invalid_diet' => '❌ कृपया शाकाहारी, मांसाहारी या दोनों में से कोई एक चुनें।',
//     'saved_diet' => '✅ आपका भोजन विकल्प *:diet* के रूप में सेव कर लिया गया है।',

//     'ask_smoke' => "🚬 શું તમે *ધૂમ્રપાન* કરો છો?",
//     'saved_smoking' => "✅ તમારું ધૂમ્રપાન પસંદગી *:value* તરીકે સાચવાઈ છે.",
//     'option_yes' => "હા",
//     'option_no' => "ના",
//     'option_occasionally' => "ક્યારેક",

//     'drinking_question' => "🍻 શું તમે *દારૂ પીતા/પીતા છો*?",
//     'drinking_saved' => "✅ પીવાનું પસંદગી *:value* તરીકે સાચવવામાં આવી છે.",
//     'yes' => 'હા',
//     'no' => 'ના',
//     'occasionally' => 'ક્યારેક ક્યારેક',

//     'ask_height' => "📏 કૃપા કરીને તમારી *ઉંચાઈ* સેમી માં દાખલ કરો (જેમ કે 170):",
//     'height_invalid' => "❌ માન્ય ઉંચાઈ દાખલ કરો (જેમ કે 170 સેમી).",
//     'height_saved' => "✅ ઉંચાઈ *:value* સેમી તરીકે સંગ્રહાઈ છે.",

//     'ask_body_type' => "🏋️‍♂️ તમારું *શરીર ટાઈપ* શું છે?",
//     'body_type_saved' => "✅ શરીર ટાઈપ *:value* તરીકે સાચવાયું છે.",
//     'body_type_slim' => 'પાતળું',
//     'body_type_athletic' => 'એથલેટિક',
//     'body_type_average' => 'સરેરાશ',
//     'body_type_heavy' => 'ભારે',

//     'ask_skin_tone' => "🧜‍♀️ કૃપા કરીને તમારું *ત્વચા રંગ* પસંદ કરો:",
//     'skin_tone_saved' => "✅ त्वचा રંગ *:value* તરીકે સાચવાયો છે.",
//     'ask_life_partner_intro' => "તમારા જીવનસાથી વિશે તમે શું ઈચ્છો છો તે અમને જણાવો.",
//     'skin_fair' => 'ગોરું',
//     'skin_wheatish' => 'ઘઉંવર્ણ',
//     'skin_dusky' => 'થોડું કાળું',
//     'skin_dark' => 'કાળો',


//     'ask_partner_marital_status' => "💍 તમારા *પસંદીદા જીવનસાથીની લગ્ન સ્થિતિ* શું હોવી જોઈએ?",
//     'partner_marital_status_saved' => "✅ જીવનસાથીની લગ્ન સ્થિતિ *:value* તરીકે સંગ્રહવામાં આવી છે.",
//     'status_single' => 'અવિવાહિત',
//     'status_divorced' => 'છૂટાછેડા લીધો',
//     'status_widowed' => 'વિધવા/વિધુર',

//     'ask_partner_caste' => "🙏 કૃપા કરીને તમારા *પસંદીદા જીવનસાથીની જાતિ* પસંદ કરો:",
//     'partner_caste_saved' => "✅ પસંદ કરેલ જીવનસાથીની જાતિ *:value* તરીકે સાચવવામાં આવી છે.",
//     'caste_hindu' => 'હિન્દૂ',
//     'caste_muslim' => 'મુસ્લિમ',
//     'caste_christian' => 'ઈસાઈ',
//     'caste_sikh' => 'સીખ',
//     'caste_jain' => 'જૈન',
//     'caste_buddhist' => 'બૌદ્ધ',
//     'caste_other' => 'અન્ય',

//     'ask_partner_min_age' => "🎂 તમારા જીવનસાથી માટે *ન્યૂનતમ ઉમર* શું હોવી જોઈએ?",
//     'partner_min_age_saved' => "✅ પસંદ કરેલ ન્યૂનતમ ઉમર *:value* તરીકે સાચવવામાં આવી છે.",
//     'example_age' => 'દા.ત., 25',

//     'partner_max_age_question' => "🎂 તમારા જીવનસાથી માટેની *અતિમ પસંદગીની ઉંમર* શું છે?",
//     'partner_max_age_saved' => "✅ અતિમ ઉંમર *:value* તરીકે સાચવવામાં આવી છે.",
//     'partner_max_age_invalid' => "❌ કૃપા કરીને 18 થી 100 વચ્ચેની માન્ય ઉંમર દાખલ કરો.",

//     'partner_min_height_question' => "📏 તમારા જીવનસાથી માટેની *ઓછામાં ઓછી ઊંચાઈ* શું છે?",
//     'partner_min_height_saved' => "✅ ઓછી ઊંચાઈ *:value* તરીકે સાચવવામાં આવી છે.",

//     'partner_max_height_question' => "📏 તમારા જીવનસાથી માટેની *મહત્તમ ઊંચાઈ* શું છે?",
//     'partner_max_height_saved' => "📏 મહત્તમ ઊંચાઈ *:value* તરીકે સાચવવામાં આવી છે.",

//     'partner_gender_question' => "👫 તમારા *પસંદીદા જીવનસાથીનું લિંગ* શું છે?",
//     'partner_gender_saved' => "✅ જીવનસાથીનું લિંગ *:value* તરીકે સાચવવામાં આવ્યું છે.",

//     'partner_language_question' => "🌐 તમારા જીવનસાથીની *પસંદીદા ભાષા* શું છે?",











// ];


return [

    'language_select' => "🌐 કૃપા કરીને તમારી ભાષા પસંદ કરો",
    'registration_welcome' => "💖 *LoveConnectમાં તમારું સ્વાગત છે!* 💖\n\nચાલો તમારી થોડી માહિતીથી શરૂઆત કરીએ!",
    'ask_name' => "👉 *તમારું નામ શું છે?*",
    'thanks_name' => "✅ આભાર, :name!",

    'ask_bio' => "📝 કૃપા કરીને તમારા વિશે ટૂંકો *પરિચય* લખો (મહત્તમ 255 અક્ષરો):",
    'thanks_bio' => "✅ આભાર! તમારો પરિચય સાચવાયો છે:\n\n:bio",
    'bio_too_long' => "❌ પરિચય બહુ લાંબો છે. મહત્તમ :max અક્ષરો સુધી લખો.",

    'ask_email' => "📧 કૃપા કરીને તમારું ઇમેઇલ સરનામું દાખલ કરો.",
    'thanks_email' => "✅ આભાર! તમારું ઇમેઇલ (:email) સંગ્રહિત કરવામાં આવ્યું છે.",

    'ask_gender' => "👩👨 કૃપા કરીને તમારું *લિંગ* પસંદ કરો:",
    'thanks_gender' => "✅ લિંગ *:gender* તરીકે સંગ્રહિત થયું છે.",
    'gender_male' => "પુરૂષ",
    'gender_female' => "મહિલા",
    'gender_other' => "અન્ય",

    'ask_marital_status' => "💍 કૃપા કરીને તમારું વૈવાહિક સ્થિતિ પસંદ કરો:",
    'thanks_marital_status' => "✅ વૈવાહિક સ્થિતિ *:status* તરીકે સંગ્રહિત થઈ છે.",
    'status_single' => "અવિવાહિત",
    'status_married' => "વિવાહિત",
    'status_divorced' => "છૂટાછેડા થયેલ",
    'status_any' => "કોઈપણ",

    'ask_dob' => "📅 કૃપા કરીને તમારી જન્મ તારીખ `DD-MM-YYYY` ફોર્મેટમાં દાખલ કરો:",
    'dob_placeholder' => "DD-MM-YYYY",
    'invalid_dob_format' => "❌ ખોટો ફોર્મેટ. કૃપા કરીને `DD-MM-YYYY` ફોર્મેટમાં દાખલ કરો, જેમ કે *13-07-1998*.",
    'thanks_dob' => "✅ જન્મ તારીખ *:dob* તરીકે સંગ્રહિત થઈ છે.",

    'ask_state' => "🏞️ કૃપા કરીને તમારું *રાજ્ય* પસંદ કરો:",
    'thanks_state' => "✅ રાજ્ય *:state* તરીકે સંગ્રહિત થયું છે.",

    'ask_city' => "🏙️ કૃપા કરીને તમારું *શહેર* પસંદ કરો:",
    'thanks_city' => "✅ શહેર *:city* તરીકે સંગ્રહિત થયું છે.",
    'select_state_first' => "કૃપા કરીને પહેલા રાજ્ય પસંદ કરો",

    'ask_mother_tongue' => "🗣️ કૃપા કરીને તમારી *માતૃભાષા* પસંદ કરો:\nજો સૂચિમાં ન હોય તો, કૃપા કરીને ટાઇપ કરો.",
    'thanks_mother_tongue' => "✅ *:tongue* સંગ્રહિત થઈ ગઈ છે. કૃપા કરીને રાહ જુઓ...",
    'tongue_hindi' => 'હિન્દી',
    'tongue_marathi' => 'મરાઠી',
    'tongue_gujarati' => 'ગુજરાતી',
    'tongue_punjabi' => 'પંજાબી',
    'tongue_tamil' => 'તમિલ',
    'tongue_telugu' => 'તેલુગુ',

    'ask_religion' => "🙏 કૃપા કરીને તમારું *ધર્મ* પસંદ કરો:\nજો સૂચિમાં ન હોય તો, કૃપા કરીને ટાઇપ કરો.",
    'thanks_religion' => "✅ ધર્મ *:religion* તરીકે સંગ્રહિત થયો છે.",
    'religion_hindu' => 'હિન્દુ',
    'religion_muslim' => 'મુસ્લિમ',
    'religion_christian' => 'ઈસાઈ',
    'religion_sikh' => 'સિખ',
    'religion_buddhist' => 'બૌદ્ધ',
    'religion_jain' => 'જૈન',

    'ask_caste' => "🧬 કૃપા કરીને તમારી *જાતિ* પસંદ કરો અથવા ટાઇપ કરો:",
    'thanks_caste' => "✅ જાતિ *:caste* તરીકે સંગ્રહિત થઈ છે.",
    'caste_brahmin' => 'બ્રાહ્મણ',
    'caste_kshatriya' => 'ક્ષત્રિય',
    'caste_vaishya' => 'વૈશ્ય',
    'caste_shudra' => 'શૂદ્ર',

    'ask_education' => "🎓 કૃપા કરીને તમારી *ઉચ્ચતમ શિક્ષણ સ્તર* પસંદ કરો:\nજો સૂચિમાં ન હોય તો, કૃપા કરીને ટાઇપ કરો.",
    'thanks_education' => "✅ શિક્ષણ સ્તર *:education* તરીકે સંગ્રહિત થયું છે.",
    'education_highschool' => 'હાઈ સ્કૂલ',
    'education_diploma' => 'ડિપ્લોમા',
    'education_bachelor' => 'સ્નાતક',
    'education_master' => 'સ્નાતકોત્તર',
    'education_phd' => 'પીએચડી',

    'ask_education_field' => "🔬 કૃપા કરીને તમારું *અભ્યાસ ક્ષેત્ર* દાખલ કરો (જેમ કે Engineering, Arts, Commerce):",
    'thanks_education_field' => "✅ અભ્યાસ ક્ષેત્ર *:field* તરીકે સંગ્રહિત થયું છે.",
    'field_engineering' => 'ઇજનેરી',
    'field_arts' => 'કલા',
    'field_commerce' => 'વાણિજ્ય',
    'field_science' => 'વિજ્ઞાન',

    'ask_job_status' => '💼 કૃપા કરીને તમારું વર્તમાન નોકરીની સ્થિતિ પસંદ કરો:',
    'thanks_job_status' => '✅ નોકરીની સ્થિતિ *:status* તરીકે સંગ્રહિત થઈ છે.',

    'job_employed' => 'નોકરીમાં',
    'job_self_employed' => 'સ્વ-નિયોજિત',
    'job_student' => 'વિદ્યાર્થી',
    'job_unemployed' => 'બેરોજગાર',

    'job_service' => 'સેવા',
    'job_business' => 'વ્યવસાય',
    'job_home_business' => 'ઘર આધારિત વ્યવસાય',
    'job_house_maker' => 'ઘર સંભાળનાર',

    'ask_working_sector' => "💼 કૃપા કરીને તમારું *કાર્ય ક્ષેત્ર* પસંદ કરો:",
    'thanks_working_sector' => "✅ કાર્ય ક્ષેત્ર *:sector* તરીકે સંગ્રહિત થયું છે.",
    'sector_private' => 'ખાનગી',
    'sector_government' => 'સરકારી',
    'sector_business' => 'વ્યવસાય',
    'sector_freelance' => 'ફ્રીલાન્સ',
    'sector_student' => 'વિદ્યાર્થી',
    'sector_not_working' => 'કામ કરતો નથી',

    'ask_profession' => "💼 કૃપા કરીને તમારું *વ્યવસાય* દાખલ કરો (જેમ કે Software Engineer, Doctor, Teacher):",
    'thanks_profession' => "✅ વ્યવસાય *:profession* તરીકે સંગ્રહિત થયો છે.",
    'profession_software_engineer' => 'સોફ્ટવેર ઈજનેર',
    'profession_doctor' => 'ડોક્ટર',
    'profession_teacher' => 'શિક્ષક',
    'profession_businessman' => 'વ્યવસાયી',
    'profession_student' => 'વિદ્યાર્થી',
    'profession_house_maker' => 'ઘર સંભાળનાર',

    'ask_mobile' => "📱 કૃપા કરીને તમારું 10-અંકનું મોબાઇલ નંબર દાખલ કરો (6–9 થી શરૂ થતું):",
    'invalid_mobile' => "❌ ખોટો નંબર. કૃપા કરીને માન્ય 10-અંકનું નંબર દાખલ કરો.",
    'thanks_mobile' => "✅ મોબાઇલ નંબર *:mobile* તરીકે સંગ્રહિત થયો છે.",

    'ask_profile_photo' => "📸 કૃપા કરીને તમારી પ્રોફાઇલ ફોટો એક છબી તરીકે મોકલાવો:",
    'skip_photo' => 'ફોટો છોડો',
    'upload_another_photo' => 'બીજી ફોટો અપલોડ કરો',
    'profile_photo_skipped' => "✅ સમજાયું! હવે આગળ વધીએ.",
    'profile_photo_invalid_text' => "❌ કૃપા કરીને માન્ય ફોટો મોકલાવો અથવા *Done* ટૅપ કરો.",
    'profile_photo_invalid' => "❌ કૃપા કરીને માન્ય ફોટો મોકલાવો.",
    'profile_photo_process_failed' => "❌ ફોટો પ્રક્રિયા નિષ્ફળ.",
    'profile_photo_save_failed' => "❌ ફોટો સાચવી શક્યા નહીં.",
    'profile_photo_uploaded' => "🖼️ ફોટો તમારી ગેલેરીમાં ઉમેરાયો છે!",

    // ✅ Diet section added here
    'ask_diet' => '🍽️ *તમારું આહાર પસંદગિ શું છે?*\nકૃપા કરીને પસંદ કરો: શાકાહારી, મांसાહારી કે બંને.',
    'invalid_diet' => '❌ કૃપા કરીને શાકાહારી, મांसાહારી કે બંનેમાંથી પસંદ કરો.',
    'saved_diet' => '✅ તમારું આહાર *:diet* તરીકે સંગ્રહિત થયું છે.',
    'diet_veg' => 'શાકાહારી',
    'diet_nonveg' => 'મांसાહારી',
    'diet_both' => 'બંને',

    'ask_smoke' => "🌬️ શું તમે *ધૂમ્રપાન* કરો છો?",
    'saved_smoking' => "✅ ધૂમ્રપાન પસંદગી *:value* તરીકે સંગ્રહિત થઈ છે.",
    'option_yes' => "હા",
    'option_no' => "ના",
    'option_occasionally' => "વખતે-વખતે",

    'drinking_question' => "🍻 શું તમે *મદિરા સેવન* કરો છો?",
    'drinking_saved' => "✅ મદિરા સેવન પસંદગી *:value* તરીકે સંગ્રહિત થઈ છે.",
    'yes' => 'હા',
    'no' => 'ના',
    'occasionally' => 'વખતે-વખતે',

    'ask_height' => "📏 કૃપા કરીને તમારું ઊંચાઈ ફૂટ અને ઈંચમાં લખો (દા.ત. 5.2 એટલે કે 5 ફૂટ 2 ઈંચ)",
    'height_invalid' => "❌ ખોટી ઊંચાઈ. કૃપા કરીને 5.2 જેવા ફોર્મેટમાં દાખલ કરો.",
    'height_saved' => "✅ તમારી ઊંચાઈ *:value* તરીકે સંગ્રહિત થઈ છે.",

    'ask_body_type' => "🏋️‍♂️ તમારું *શરીર પ્રકાર* શું છે?",
    'body_type_saved' => "✅ શરીર પ્રકાર *:value* તરીકે સંગ્રહિત થયો છે.",
    'body_type_slim' => 'સ્લિમ',
    'body_type_athletic' => 'અથલેટિક',
    'body_type_average' => 'સરેરાશ',
    'body_type_heavy' => 'ભારભાર',

    'ask_skin_tone' => "🧜‍♀️ કૃપા કરીને તમારું *ત્વચા રંગ* પસંદ કરો:",
    'skin_tone_saved' => "✅ ત્વચા રંગ *:value* તરીકે સંગ્રહિત થયો છે.",
    'skin_fair' => 'ઉજળી',
    'skin_wheatish' => 'ગહન પીળો',
    'skin_dusky' => 'ઘાટો',
    'skin_dark' => 'અંધારું',

    'ask_life_partner_intro' => "તમારા જીવનસાથીને તમે કેવી રીતે જોવો છો તે અમને જણાવો.",

    'ask_partner_marital_status' => "💍 તમારા *પસંદીદા જીવનસાથીનું વૈવાહિક સ્થિતિ* શું હોવી જોઈએ?
     જો સૂચિમાં ન હોય તો, કૃપા કરીને ટાઈપ કરો.",
    'partner_marital_status_saved' => "✅ જીવનસાથીની વૈવાહિક સ્થિતિ *:value* તરીકે સંગ્રહિત થઈ છે.",

    'status_single' => 'અવિવાહિત',
    'status_divorced' => 'છૂટાછેડા થયેલ',
    'status_widowed' => 'વિધવા/વિધુર',
    'status_any' => 'કોઈપણ',

    'ask_partner_caste' => "🙏 કૃપા કરીને તમારા *પસંદીદા જીવનસાથીની જાતિ* પસંદ કરો:
     જો સૂચિમાં ન હોય તો, કૃપા કરીને ટાઈપ કરો.",
    'partner_caste_saved' => "✅ જીવનસાથીની જાતિ *:value* તરીકે સંગ્રહિત થઈ છે.",
    'caste_hindu' => 'હિન્દુ',
    'caste_muslim' => 'મુસ્લિમ',
    'caste_christian' => 'ઈસાઈ',
    'caste_sikh' => 'સિખ',
    'caste_jain' => 'જૈન',
    'caste_buddhist' => 'બૌદ્ધ',
    'caste_any' => 'કોઈપણ',

    'ask_partner_min_age' => "🎂 તમારા જીવનસાથી માટેનું *ઘટતમ ઉંમર* શું હોવું જોઈએ?",
    'partner_min_age_saved' => "✅ પસંદગીની ઘટાડેલી ઉંમર *:value* તરીકે સંગ્રહિત થઈ છે.",
    'example_age' => 'દા.ત. 25',

    'partner_max_age_question' => "🎂 તમારા જીવનસાથી માટેનું *વિશિષ્ટ મહત્તમ ઉંમર* શું છે?",
    'partner_max_age_saved' => "✅ મહત્તમ ઉંમર *:value* તરીકે સંગ્રહિત થઈ છે.",
    'partner_max_age_invalid' => "❌ કૃપા કરીને 18 થી 100 વચ્ચે માન્ય ઉંમર દાખલ કરો.",

    // 'partner_min_height_question' => "📏 જીવનસાથી માટેની *ન્યૂનતમ ઊંચાઈ* શું છે?",
    // 'partner_min_height_saved' => "✅ ન્યૂનતમ ઊંચાઈ *:value* તરીકે સંગ્રહિત થઈ છે.",
    'partner_min_height_question' => "📏 તમારા જીવનસાથી માટે *ઓછામાં ઓછી ઊંચાઈ* શું હોવી જોઈએ?\nકૃપા કરીને 5.2 જેવા મૂલ્ય દાખલ કરો (જેમ કે: 5 ફૂટ 2 ઇંચ).",
    'partner_min_height_saved' => "✅ ઓછી ઊંચાઈ *:value* તરીકે સાચવવામાં આવી છે.",
    'height_invalid' => "❌ કૃપા કરીને માન્ય ઊંચાઈ દાખલ કરો જેમ કે 5.2 (5 ફૂટ 2 ઇંચ).",

    'partner_max_height_question' => "📏 તમારા જીવનસાથી માટે *વધુતમ ઊંચાઈ* શું હોવી જોઈએ?\nકૃપા કરીને 5.2 જેવા મૂલ્ય દાખલ કરો (જેમ કે: 5 ફૂટ 2 ઇંચ).",
    'partner_max_height_saved' => "✅ વધુ ઊંચાઈ *:value* તરીકે સાચવવામાં આવી છે.",
    'height_invalid' => "❌ કૃપા કરીને માન્ય ઊંચાઈ દાખલ કરો જેમ કે 5.2 (5 ફૂટ 2 ઇંચ).",

    'partner_language_question' => "🌐 તમારા જીવનસાથીની *માતૃભાષા* શું હોવી જોઈએ?
    જો સૂચિમાં ન હોય તો, કૃપા કરીને ટાઈપ કરો.",

    'profile_already_complete' => '✅ તમારું પ્રોફાઇલ પહેલેથી જ સંપૂર્ણ છે! તમે /start લખીને કોઇપણ સમયે જોઈ અથવા સંપાદિત કરી શકો છો.',
];




