import pymysql
import pandas as pd
import os
from tkinter import messagebox
from warnings import filterwarnings

# 创建数据库函数


class MY_C:

    def __init__(self):
        # 配置基本参数
        self.DATABASE_NAME = 'r34'  # 数据库名
        self.TABLE_NAME = 'qw_unisecret'  # 数据表名
        self.DB_ADMIN = 'root'  # 数据库管理员账号
        self.DB_PW = 'admin'  # 数据库管理员密码
        self.SHEET_NAME = '机场查询'  # 导入表的名称
        self.CSV_NAME = 'res.csv'  # 要导入的CSV文件名称
        self.CHUNK_SIZE = 1000  # 根据电脑配置，选择合适大小，牛逼电脑数据大点，搓电脑就小点
        self.WRPW = '123456'  # 默认上传密码
        self.RPW = '654321'  # 默认查看密码
        # 要索引的列，重要
        self.NAME_COL = 2       # NAME所在的列
        self.PID_COL =  4       # pid所在的列
        # 要索引的列，重要
        self.CSV_PATH = os.path.abspath('./') + '/res/' + self.CSV_NAME
        self.ADDorNew = True  # Flase 表示全新建表，True 表示增量加纪录
















    def creat_db(self):
        try:
            conn = pymysql.connect(host='localhost',
                                   user=self.DB_ADMIN,
                                   password=self.DB_PW,
                                   charset='utf8')
            cursor = conn.cursor()

            sql = "create database if not exists {0}".format(
                self.DATABASE_NAME)  # 指定名字的数据库不存在就创建数据库
            cursor.execute(sql)
            return 1
        except Exception as e:
            print(str(e))
            return 0

    # 创建表格函数
    def creat_table(self):
        try:
            conn = pymysql.connect(host='localhost',
                                   user=self.DB_ADMIN,
                                   password=self.DB_PW,
                                   charset='utf8',
                                   database=self.DATABASE_NAME)
            cur = conn.cursor()
            fields = [
                'id int not null auto_increment primary key,',
                'sheetname varchar(255),',
                'ord float,',
                'wrpw varchar(255),',
                'rpw varchar(255) default "123", ',
                'name varchar(255),',
                'pid varchar(255),',
                'custom1 varchar(255),',
                'custom2 varchar(255),',
            ]
            for row_num in range(1, 51):
                fields.append('d{0} TEXT,'.format(row_num))
            fields.append('data1 varchar(255),')
            fields.append('data2 varchar(255)')
            head_text = 'create table {0}('.format(self.TABLE_NAME)
            tail_text = ')engine=innodb charset=utf8;'
            sql_create_table = head_text + \
                "".join(fields) + tail_text  # 创建数据表的SQL
            sql_del_table = "DROP TABLE IF EXISTS {0}".format(
                self.TABLE_NAME)  # 删除已有数据表的SQL

            cur.execute(sql_del_table)  # 执行删除数据表操作
            cur.execute(sql_create_table)  # 执行创建数据表操作
            return 1
        except Exception as e:
            print(str(e))
            return 0

    def insert_data(self):
        my_csv = pd.read_csv(self.CSV_PATH, sep=',', iterator=True,
                             low_memory=False, chunksize=self.CHUNK_SIZE, encoding='utf8')
        for each in my_csv:
            for row in each.values:
                field_num = len(row)  # 获取CSV文件中的纪录字段数
                break
            break

        # 定义表的几列必填字段
        fields = [
            'sheetname',  # 来源表名
            'wrpw',  # 上传密码
            'rpw',  # 查看密码
            'name',  # 用户名
            'pid',  # pid
        ]

        all_fields_num = len(fields) + field_num  # 获取总的字段长度

        for i in range(field_num):
            fields.append('d{0}'.format(i + 1))
            # 合成要输入的字段名字符串
        fields = ",".join(fields)

        fields_values_canshu = ','.join(["%s" for x in range(all_fields_num)])
        # 创建SQL语旬模板
        sql = 'INSERT INTO {0}({1}) values ({2})'.format(
            self.TABLE_NAME, fields, fields_values_canshu)
        conn = pymysql.connect(
            host='localhost',
            user=self.DB_ADMIN,
            password=self.DB_PW,
            database=self.DATABASE_NAME,
            charset='utf8',
        )
        cur = conn.cursor()  # 创建游戏标
        try:
            # 插入表头
            firstrow = pd.read_csv(self.CSV_PATH, nrows=0).columns.tolist()
            first_data = [self.SHEET_NAME, self.WRPW, self.RPW, firstrow[self.NAME_COL-1], firstrow[self.PID_COL-1]]
            first_data = first_data + firstrow
            cur.execute(sql, first_data)
            conn.commit()

            # 插入其余纪录
            for each in my_csv:
                for row in each.values:
                    name = row[self.NAME_COL - 1]
                    if row[self.PID_COL - 1]:
                        pid = row[self.PID_COL - 1]
                    else:
                        pid = "Null"
                    data = [self.SHEET_NAME, self.WRPW, self.RPW, name, pid]
                    data.extend(row)
                    data = [str(w).replace('nan', 'Null') for w in data]
                    if len(data) == all_fields_num:
                        cur.execute(sql, data)

            messagebox.showinfo("提示", "操作成功")
            conn.commit()
            cur.close()
            conn.close()
        except Exception as e:
            print('出现如下错误' + str(e))


# 进入主程序
if __name__ == "__main__":
    filterwarnings('ignore', category=pymysql.Warning)
    data_control = MY_C()
    data_control.creat_db()
    if not data_control.ADDorNew:
        data_control.creat_table()
    data_control.insert_data()














